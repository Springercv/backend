<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\City;
use App\Models\Country;
use App\Repositories\Interfaces\CityRepositoryInterface;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Repositories\Interfaces\LocationRepositoryInterface;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Requests\LocationStoreRequest as StoreRequest;
use App\Http\Requests\LocationUpdateRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class LocationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class LocationCrudController extends BaseCrudController
{
    public function __construct(CityRepositoryInterface $cityRepository, CountryRepositoryInterface $countryRepository,
                                LocationRepositoryInterface $locationRepository)
    {
        parent::__construct();
        $this->cityRepository = $cityRepository;
        $this->countryRepository = $countryRepository;
        $this->locationRepository = $locationRepository;
    }

    public function setup()
    {
        parent::setup();
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Location');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/location');
        $this->crud->setEntityNameStrings('location', 'locations');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->setColumns();
        $this->setFields();
        
        // add asterisk for fields that are required in LocationRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    private function setColumns()
    {
        //----Column----//
        $this->crud->addColumn([
            'name' => 'name',
            'label' => 'Name',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'type' => 'text',
            'name' => 'city.name',
            'label' => 'City',
            'orderable' => true,
            'orderLogic' => function ($query, $column, $column_direction) {
                return $query->join('cities', 'cities.id', '=', 'locations.city_id')
                    ->orderBy('cities.name', $column_direction);
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('city', function ($q) use ($column, $searchTerm) {
                    $q->where('name', 'like', '%'.$searchTerm.'%');
                });
            },
        ]);

        $this->crud->addColumn([
            'type' => 'text',
            'name' => 'city.country.name',
            'label' => 'Country',
            'orderable' => true,
            'orderLogic' => function ($query, $column, $column_direction) {
                return $query->join('cities', 'cities.id', '=', 'locations.city_id')
                    ->join('countries', 'countries.id', '=', 'cities.country_id')
                    ->orderBy('countries.name', $column_direction);
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('city', function ($q) use ($column, $searchTerm) {
                    $q->where('name', 'like', '%'.$searchTerm.'%');
                });
            },
        ]);
    }

    private function setFields()
    {
        $this->crud->addField([
            'name' => 'name',
            'label' => "Name",
            'type' => 'text'
        ]);

        $this->crud->addField([
            'label' => "Country",
            'type' => "select2_from_ajax_custom",
            'name' => 'countries',
            'name_input' => 'country_id',
            'model' => Country::class,
            'attribute' => "name",
            'options' => function($query) {
                return $query->get();
            },
            'value' => function($entry) {
                return $entry && $entry->city ? $entry->city->country() : collect([]);
            },
            'data_source' => route('admin.city.get-city-of-country'),
            'placeholder' => "Select a city", // placeholder for the select,
            'select2_change_id' => 'select2_city',
        ]);

        $this->crud->addField([  // Select2
            'label' => "City",
            'type' => 'select2_custom',
            'name' => 'city_id', // the db column for the foreign key
            'entry' => 'city',
            'attribute' => 'name', // foreign key attribute that is shown to user
            'attributes'=> [
              'id' => 'select2_city'
            ],
            'options' => function($entry) {
                return $entry ? collect([$entry->city]) : collect([]);
            },
            'multiple' => false
        ]);
    }

    public function store(StoreRequest $request)
    {
        $redirect_location = parent::storeCrud($request);
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function getLocationOfCountry(Request $request)
    {
        if ($request->ajax() && $request->has('country_id')) {
            $countryIds = is_array($request->country_id) ? $request->country_id : [$request->country_id];
            $cityIds = $this->cityRepository->findCityOfCountry($countryIds)->pluck('id')->toArray();
            $countries = $this->locationRepository->findLocationOfCity($cityIds)->pluck('name', 'id');
            return response()->json($countries);
        }
        return response()->json([]);
    }

    public function getLocationOfCity(Request $request)
    {
        if ($request->ajax() && $request->has('city_id')) {
            $cityIds = is_array($request->city_id) ? $request->city_id : [$request->city_id];
            $countries = $this->locationRepository->findLocationOfCity($cityIds)->pluck('name', 'id');
            return response()->json($countries);
        }
        return response()->json([]);
    }
}
