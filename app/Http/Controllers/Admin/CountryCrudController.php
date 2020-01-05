<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CountryStoreRequest as StoreRequest;
use App\Http\Requests\CountryUpdateRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class CountryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CountryCrudController extends BaseCrudController
{
    public function setup()
    {
        parent::setup();
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Country');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/country');
        $this->crud->setEntityNameStrings('country', 'countries');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->setupColumns();
        $this->setupFields();
        // TODO: remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();

        // add asterisk for fields that are required in CountryRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    private function setupColumns()
    {
        //----Column----//
        $this->crud->addColumn([
            'name' => 'name',
            'label' => 'Name',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'ensigns', // The db column name
            'label' => "Ensign", // Table column heading
            'type' => 'image_custom',
            'height' => '50px',
            'closure' => function($entry) {
                return @$entry->images->first()->url;
            }
        ]);
    }

    private function setupFields()
    {

        //----Field----//
        $this->crud->addField([
            'name' => 'name',
            'label' => "Name",
            'type' => 'text'
        ]);

        $this->crud->addField([
            'name' => 'images',
            'label' => 'Ensign',
            'type' => 'virals_browse_image',
        ]);
    }

    public function store(StoreRequest $request)
    {
        $redirect_location = parent::storeCrud($request);
        $this->crud->entry->createImage($request->images);
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $redirect_location = parent::updateCrud($request);
        $this->crud->entry->updateImage($request->images);
        return $redirect_location;
    }
}