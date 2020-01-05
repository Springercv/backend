<?php
namespace App\Repositories;


use App\Models\Country;
use App\Repositories\Interfaces\CountryRepositoryInterface;

class CountryRepository extends BaseRepository implements CountryRepositoryInterface
{
    public function __construct()
    {
        $this->modelClass = Country::class;
    }
}