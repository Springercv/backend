<?php
namespace App\Repositories\Interfaces;


interface CityRepositoryInterface
{
    public function findCityOfCountry($countryIds);
}