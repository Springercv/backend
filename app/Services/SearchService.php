<?php
namespace App\Services;

use App\Models\Tour;
use App\Repositories\Interfaces\MasterSearchRepositoryInterface;
use App\Services\Interfaces\SearchServiceInterface;
use Carbon\Carbon;

class SearchService implements SearchServiceInterface
{
    const SEARCH_LIKE_STRING = 1;
    const SEARCH_BETWEEN = 2;
    const SEARCH_EQUAL = 3;
    const SEARCH_GREATER_EQUAL = 4;
    const SEARCH_LESS_EQUAL = 5;
    const SEARCH_LIMIT_DEFAULT_RECORD = 10;

    private $countWhereModel;
    private $countWhereRelation;

    public function __construct(MasterSearchRepositoryInterface $masterSearchRepository)
    {
        $this->masterSearchRepository = $masterSearchRepository;
        $this->countWhereModel = 0;
        $this->countWhereRelation = 0;
    }

    public function parseMasterSearchValue(&$result, $value, $searchValue)
    {
        $tables = explode(';', $value);
        foreach ($tables as $table) {
            $table = explode(':', $table);
            $tableName = $table[0];
            $columns = explode(',', $table[1]);
            foreach ($columns as $column) {
                $column = explode('-', $column);
                $result[$tableName][$column[0]]['values'] = $result[$tableName][$column[0]]['values'] ?? [];
                $result[$tableName][$column[0]]['type_search'] = $column[1];
                $result[$tableName][$column[0]]['values'] = array_filter(array_unique(array_merge($result[$tableName][$column[0]]['values'], $searchValue)));
            }
        }
    }

    public function search($keys = [], $modelClass = null)
    {
        $keys = is_array($keys) ? $keys : [$keys];
        $result = collect([]);
        $searchValues = [];
        //parseMasterSearchValue
        foreach ($keys as $key => $searchValue) {
            $record = $this->masterSearchRepository->findByAttrFirst('key', $key);
            if (!is_null($record)) {
                $searchValue = explode(',', $searchValue);
                foreach ($searchValue as $index => $value) {
                    $tempDate = explode('-', $value);
                    $searchValue[$index] = checkdate((int)@$tempDate[1], (int)@$tempDate[2], (int)@$tempDate[0]) ? Carbon::parse($value) : $value;
                }
                $this->parseMasterSearchValue($searchValues, $record->value, $searchValue);
            }
        }
        //search
        if (!is_null($modelClass)) {
            $result = $this->searchModel($modelClass, $searchValues);
        }

        return $result;
    }

    public function searchModel($modelClass, $searchValues)
    {
        $queryModel = $modelClass::query();
        $queryRaltion = $modelClass::query();
        foreach ($searchValues as $relationName => $columns) {
            foreach ($columns as $columnName => $data) {
                $type = $data['type_search'];
                if (getModelByTablename($relationName) == $modelClass) {
                    foreach ($data['values'] as $value) {
                        $filter = substr($columnName, -1) == '*';
                        if ($filter) {
                            $columnName = substr($columnName, 0, -1);
                        }
                        $this->countWhereModel++;
                        $queryModel = $this->searchByType($type, $queryModel, $columnName, $value, $filter);
                        $queryRaltion = $this->searchByType($type, $queryRaltion, $columnName, $value, $filter);
                    }
                } else {
                    $queryRaltion = $queryRaltion->with($relationName);
                    $fisrtValue = reset($data['values']);
                    if ($fisrtValue) {
                        $this->countWhereRelation++;
                        $queryRaltion = $queryRaltion->whereHas($relationName, function($query) use ($data, $columnName, $fisrtValue, $type) {
                            $this->searchByType($type, $query, $columnName, $fisrtValue, true);
                        });
                        array_shift($data['values']);
                        foreach ($data['values'] as $value) {
                            $this->countWhereRelation++;
                            $queryRaltion = $queryRaltion->orWhereHas($relationName, function($query) use ($data, $columnName, $value, $type) {
                                $this->searchByType($type, $query, $columnName, $value, true);
                            });
                        }
                    }
                }
            }
        }
        $modelResults = $this->countWhereModel == 0 ? collect([]) : $queryModel->get();
        $relaitonResults = $this->countWhereRelation == 0 ? collect([]) : $queryRaltion->get();
        if ($relaitonResults->count()) {
            $result = $relaitonResults->filter()->unique();
        } else {
            $result = $modelResults->filter()->unique();
        }
        if (!$result->count()) {
            $result = $this->getDefaultRecords($modelClass);
        }
        return $result;
    }

    private function getDefaultRecords($modelClass)
    {
        return $modelClass::query()->latest()->limit(self::SEARCH_LIMIT_DEFAULT_RECORD)->get();
    }

    public function searchLikeString($query, $column, $value, $and = false)
    {
        if (!$and) {
            return $query->orWhere($column, 'LIKE', '%' . $value . '%');
        }
        return $query->where($column, 'LIKE', '%' . $value . '%');
    }

    public function searchBetween($query, $column, $value, $and = false)
    {
        list($from, $to) = explode('|', $value);
        if (!$and) {
            return $query->orWhereBetween($column, [$from, $to]);
        }
        return $query->whereBetween($column, [$from, $to]);
    }

    public function searchEqual($query, $column, $value, $and = false)
    {
        if (!$and) {
            return $query->orWhere($column, $value);
        }
        return $query->where($column, $value);
    }

    public function searchGreaterThanEqual($query, $column, $value, $and = false)
    {
        if (!$and) {
            return $query->orWhere($column, '>=', $value);
        }
        return $query->where($column, '>=', $value);
    }

    public function searchLessThanEqual($query, $column, $value, $and = false)
    {
        if (!$and) {
            return $query->orWhere($column, '<=', $value);
        }
        return $query->where($column, '<=', $value);
    }

    public function searchByType($type, $query, $column, $value, $and = false)
    {
        switch ($type) {
            case self::SEARCH_LIKE_STRING:
                return $this->searchLikeString($query, $column, $value, $and);
            case self::SEARCH_BETWEEN:
                return $this->searchBetween($query, $column, $value, $and);
            case self::SEARCH_EQUAL:
                return $this->searchEqual($query, $column, $value, $and);
            case self::SEARCH_GREATER_EQUAL:
                return $this->searchGreaterThanEqual($query, $column, $value, $and);
            case self::SEARCH_LESS_EQUAL:
                return $this->searchLessThanEqual($query, $column, $value, $and);
            default:
                return $query;
        }
    }

}