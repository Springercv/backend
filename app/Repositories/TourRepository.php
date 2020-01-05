<?php
namespace App\Repositories;

use App\Models\Price;
use App\Models\Tour;
use App\Repositories\Interfaces\TourRepositoryInterface;
use App\Repositories\Interfaces\PriceRepositoryInterface;

class TourRepository extends BaseRepository implements TourRepositoryInterface
{
    public function __construct(PriceRepositoryInterface $priceRepository)
    {
        $this->modelClass = Tour::class;
        $this->priceRepository = $priceRepository;
    }

    public function syncImages(Tour $model,  $imageIds = [])
    {
        return $model->images()->sync($imageIds);
    }

    public function syncLocations(Tour $model,  $locationIds = [])
    {
        return $model->locations()->sync($locationIds);
    }

    public function syncCities(Tour $model,  $cities = [])
    {
        return $model->cities()->sync($cities);
    }

    public function syncCountries(Tour $model,  $countries = [])
    {
        return $model->countries()->sync($countries);
    }

    public function syncPrices(Tour $model,  $prices = [])
    {
        $model->prices()->delete();
        foreach ($prices as $price) {
            if ($price['id'] < 0) {
               unset($price['id']);
            }
            $price['tour_id'] = $model->id;
            $this->priceRepository->updateOrCreateModel($price);
        }
    }
}