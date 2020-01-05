<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Interfaces\CityRepositoryInterface;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CityStoreRequest as StoreRequest;
use App\Http\Requests\CityUpdateRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class CityCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CityCrudController extends BaseCrudController
{
    public function __construct(CityRepositoryInterface $cityRepository)
    {
        parent::__construct();
        $this->cityRepository = $cityRepository;
    }

    public function setup()
    {
        parent::setup();
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\City');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/city');
        $this->crud->setEntityNameStrings('city', 'cities');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();
        $this->setupColumns();
        $this->setupFields();

        // add asterisk for fields that are required in CityRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
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

    private function setupFields()
    {
        $this->crud->addField([
            'name' => 'name',
            'label' => "Name",
            'type' => 'text'
        ]);

        $this->crud->addField([  // Select2
            'label' => 'Country',
            'type' => 'select2',
            'name' => 'country_id', // the db column for the foreign key
            'entity' => 'country', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Country", // foreign key model,
        ]);
    }

    private function setupColumns()
    {
        $this->crud->addColumn([
            'name' => 'name',
            'label' => 'Name',
            'type' => 'text',
            'searchLogic' => 'text'
        ]);

        $this->crud->addColumn([
            'label' => 'Country',
            'type' => 'text',
            'name' => 'country.name',
            'orderable' => true,
            'orderLogic' => function ($query, $column, $column_direction) {
                return $query->join('countries', 'countries.id', '=', 'cities.country_id')
                    ->orderBy('countries.name', $column_direction);
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('country', function ($q) use ($column, $searchTerm) {
                    $q->where('name', 'like', '%'.$searchTerm.'%');
                });
            },
        ]);
    }

    public function getCityOfCountry(Request $request)
    {
       if ($request->ajax() && $request->has('country_id')) {
           $countryIds = is_array($request->country_id) ? $request->country_id : [$request->country_id];
           $cities = $this->cityRepository->findCityOfCountry($countryIds)->pluck('name', 'id');
           return response()->json($cities);
       }
       return response()->json([]);
    }
}
