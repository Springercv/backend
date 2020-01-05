<?php
/**
 * Created by PhpStorm.
 * User: hm
 * Date: 06/03/2019
 * Time: 17:31
 */

namespace App\Repositories;


class BaseRepository
{
    public $modelClass;

    public function findByAttrFirst($attr = null, $value)
    {
        return !is_null($attr) ? $this->modelClass::where($attr, $value)->first() : null;
    }

    public function createModel($data = [])
    {
        return $this->modelClass::create($data)->fresh();
    }

    public function pluckAttrId($attr = null)
    {
        return !is_null($attr) ? $this->modelClass::pluck($attr, 'id')->all() : collect([]);
    }

    public function deleteByAttr($attr = null, $value)
    {
        return !is_null($attr) ? $this->modelClass::where($attr, $value)->delete() : false;
    }

    public function findByAttrInArray($attr = null, $array = [])
    {
        return !is_null($attr) ? $this->modelClass::whereIn($attr, $array)->get() : collect([]);
    }

    public function updateOrCreateModel($data = null)
    {
        return !is_null($data) ? $this->modelClass::updateOrCreate($data) : false;
    }
}