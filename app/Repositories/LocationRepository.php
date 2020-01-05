<?php
/**
 * Created by PhpStorm.
 * User: hm
 * Date: 07/03/2019
 * Time: 00:15
 */

namespace App\Repositories;


use App\Models\Location;
use App\Repositories\Interfaces\LocationRepositoryInterface;

class LocationRepository extends BaseRepository implements LocationRepositoryInterface
{
    public function __construct()
    {
        $this->modelClass = Location::class;
    }

    public function findLocationOfCity($cityIds = [])
    {
        return $this->findByAttrInArray('city_id', $cityIds);
    }
}