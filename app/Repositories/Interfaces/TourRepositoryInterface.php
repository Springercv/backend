<?php
namespace App\Repositories\Interfaces;

use App\Models\Tour;

interface TourRepositoryInterface
{
    public function syncImages(Tour $model, $imageIds);
    public function syncLocations(Tour $model,  $locationIds);
    public function syncCities(Tour $model,  $cities);
    public function syncCountries(Tour $model,  $countries);
}