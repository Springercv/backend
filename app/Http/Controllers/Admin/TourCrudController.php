<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Country;
use App\Models\Preference;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TourStoreRequest as StoreRequest;
use App\Http\Requests\TourUpdateRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use App\Repositories\Interfaces\TourRepositoryInterface;

/**
 * Class TourCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TourCrudController extends BaseCrudController
{
    public function __construct(TourRepositoryInterface $tourRepository, CountryRepositoryInterface $countryRepository)
    {
        parent::__construct();
        $this->tourRepository = $tourRepository;
        $this->countryRepository = $countryRepository;
    }

    public function setup()
    {
        parent::setup();
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Tour');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/tour');
        $this->crud->setEntityNameStrings('tour', 'tours');
        $this->crud->noExtraSave = true;

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->setupColumns();
        $this->setupFields();

        // add asterisk for fields that are required in TourRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    private function setupColumns()
    {
        //------------------Column---------------------//
        $this->crud->addColumn([
            'name' => 'name',
            'label' => 'Name',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'start_date',
            'label' => 'Start Date',
            'type' => 'date',
            'format' => DATE_FORMAT,
        ]);

        $this->crud->addColumn([
            'name' => 'end_date',
            'label' => 'End Date',
            'type' => 'date',
            'format' => DATE_FORMAT,
        ]);

        $this->crud->addColumn([
            'name' => 'vehicle',
            'label' => 'Vehicle',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'hotel_type',
            'label' => 'Hotel Type',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'period_date',
            'label' => "Period Date",
            'type' => "number",
        ]);

        $this->crud->addColumn([
            'name' => 'schedule',
            'label' => 'Schedule',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'description',
            'label' => 'Decription',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'note',
            'label' => 'Note',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'prices',
            'label' => 'Prices',
            'type' => 'closure',
            'function' => function($entry) {
                $result = [];
                foreach ($entry->prices as $price) {
                    $result[] = $price->price . 'VND';
                }
                return implode(';', $result);
            }
        ]);

        $this->crud->addColumn([ // Table
            'name' => 'prices',
            'label' => 'Prices',
            'type' => 'table',
            'columns' => [
                'id' => 'Id',
                'price' => 'Price',
                'start_time' => 'Start Time',
                'end_time' => 'End Time',
            ],
        ]);
    }

    private function setupFields()
    {
        //------------------Field---------------------//
        $this->crud->addField([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Name'
        ]);

        $this->crud->addField([
            'name' => 'start_date',
            'type' => 'date_picker',
            'label' => 'Start Date'
        ]);

        $this->crud->addField([
            'name' => 'end_date',
            'type' => 'date_picker',
            'label' => 'End Date'
        ]);

        $this->crud->addField([
            'name' => 'vehicle',
            'type' => 'text',
            'label' => 'Vehicle'
        ]);

        $this->crud->addField([
            'name' => 'hotel_type',
            'type' => 'text',
            'label' => 'Hotel Type'
        ]);

        $this->crud->addField([   // Number
            'name' => 'period_date',
            'label' => 'Periord Date',
            'type' => 'number',
            'attributes'=> [
                'min' => 1
            ],
        ]);

        $this->crud->addField([
            'name' => 'schedule',
            'type' => 'textarea',
            'label' => 'Schedule'
        ]);

        $this->crud->addField([
            'name' => 'description',
            'type' => 'textarea',
            'label' => 'Description'
        ]);

        $this->crud->addField([
            'name' => 'note',
            'type' => 'ckeditor',
            'label' => 'Note'
        ]);

        $this->crud->addField([
            'label' => "Country",
            'type' => "select2_from_ajax_custom",
            'name' => 'countries',
            'name_input' => 'country_id',
            'attribute' => "name",
            'options' => function($query) {
                return $query->get();
            },
            'value' => function($entry) {
                return $entry ? $entry->countries : collect([]);
            },
            'data_source' => route('admin.city.get-city-of-country'),
            'placeholder' => "Select a country", // placeholder for the select,
            'select2_change_id' => 'select2_city',
            'multiple' => true,
            'entity' => 'countries',
            'model' => Country::class,
            'pivot' => true,
        ]);

        $this->crud->addField([
            'label' => "City",
            'type' => "select2_from_ajax_custom",
            'name' => 'cities',
            'name_input' => 'city_id',
            'attribute' => "name",
            'data_source' => route('admin.location.get-location-of-city'),
            'placeholder' => "Select a city", // placeholder for the select,
            'attributes'=> [
                'id' => 'select2_city'
            ],
            'value' => function($entry) {
                return $entry ? $entry->cities : collect([]);
            },
            'multiple' => true,
            'select2_change_id' => 'select2_location',
            'entity' => 'cities',
            'model' => City::class,
            'pivot' => true,
        ]);

        $this->crud->addField([  // Select2
            'label' => "Location",
            'type' => 'select2_custom',
            'name' => 'location_id', // the db column for the foreign key
            'attribute' => 'name', // foreign key attribute that is shown to user
            'entry' => 'locations',
            'attributes'=> [
                'id' => 'select2_location'
            ],
            'options' => function($entry) {
                return $entry ? $entry->locations : collect([]);
            },
            'multiple' => true,
            'old_options' =>  @$this->crud->entry->locations,
        ]);

        $this->crud->addField([       // Select2Multiple = n-n relationship (with pivot table)
            'label' => "Preference",
            'type' => 'select2_multiple',
            'name' => 'preferences', // the method that defines the relationship in your Model
            'entity' => 'preferences', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => Preference::class, // foreign key model
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
             'select_all' => true, // show Select All and Clear buttons?
        ]);

        $this->crud->addField([
            'name' => 'images',
            'label' => 'Images',
            'type' => 'virals_browse_image',
        ]);

        $this->crud->addField([ // Table
            'name' => 'prices',
            'label' => 'Prices',
            'type' => 'table_options_custom',
            'entity_singular' => 'price', // used on the "Add X" button
            'columns' => [
                'price' => [
                    'label' => 'Price',
                    'type' => 'number',
                    'options' => []
                ],
                'start_time' => [
                    'label' => 'Start Time',
                    'options' => getMonthOfYear()
                ],
                'end_time' => [
                    'label' => 'End Time',
                    'options' => getMonthOfYear()
                ],
            ],
            'max' => 5, // maximum rows allowed in the table
            'min' => 0, // minimum rows allowed in the table
        ]);
    }

    public function store(StoreRequest $request)
    {
        $redirect_location = parent::storeCrud($request);
        $this->crud->entry->createImage($request->images);
        $this->syncDataRelation($request);
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $redirect_location = parent::updateCrud($request);
        $this->crud->entry->updateImage($request->images);
        $this->syncDataRelation($request);
        return $redirect_location;
    }

    private function syncDataRelation($request)
    {
        $this->tourRepository->syncLocations($this->crud->entry, $request->location_id);
        $this->tourRepository->syncCountries($this->crud->entry, $request->country_id);
        $this->tourRepository->syncCities($this->crud->entry, $request->city_id);
        $this->tourRepository->syncPrices($this->crud->entry, $request->prices);
    }
}
