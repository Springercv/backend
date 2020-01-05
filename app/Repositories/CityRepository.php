<?php
namespace App\Repositories;

use App\Models\City;
use App\Repositories\Interfaces\CityRepositoryInterface;

class CityRepository extends BaseRepository implements CityRepositoryInterface
{
    public function __construct()
    {
        $this->modelClass = City::class;
    }

    public function findCityOfCountry($countryIds = [])
    {
        return $this->findByAttrInArray('country_id', $countryIds);
    }
}