<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tour;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PriceStoreRequest as StoreRequest;
use App\Http\Requests\PriceUpdateRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class PriceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PriceCrudController extends BaseCrudController
{
    public function setup()
    {
        parent::setup();
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Price');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/price');
        $this->crud->setEntityNameStrings('price', 'prices');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->setupColumns();
        $this->setupFields();

        // TODO: remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();

        // add asterisk for fields that are required in PriceRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    private function setupColumns()
    {
        $this->crud->addColumn([
            'name' => 'tour.name',
            'label' => 'Name',
            'type' => 'text',
        ]);

        $this->crud->addColumn([
            'name' => 'price',
            'label' => "Price",
            'type' => 'text_custom',
            'closure' => function($value) {
                return number_format($value);
            },
            'suffix' => " USD",
        ]);

        $this->crud->addColumn([
            'name' => 'start_time',
            'label' => 'Start Time Apply Price',
            'type' => 'text_custom',
            'closure' => function($value) {
                return getMonthOfYear()[$value];
            }
        ]);

        $this->crud->addColumn([
            'name' => 'end_time',
            'label' => 'End Time Apply Price',
            'type' => 'text_custom',
            'closure' => function($value) {
                return getMonthOfYear()[$value];
            }
        ]);
    }

    private function setupFields()
    {
        $this->crud->addField([  // Select
            'label' => "Tour",
            'type' => 'select2',
            'name' => 'tour_id', // the db column for the foreign key
            'entity' => 'tour', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => Tour::class // foreign key model
        ]);
        $this->crud->addField([
            'name' => 'start_time',
            'label' => "Start Time Apply Price",
            'type' => 'select2_from_array',
            'options' => getMonthOfYear(),
            'allows_null' => false,
        ]);

        $this->crud->addField([
            'name' => 'end_time',
            'label' => "End Time Apply Price",
            'type' => 'select2_from_array',
            'options' => getMonthOfYear(),
            'allows_null' => false,
        ]);

        $this->crud->addField([   // Number
            'name' => 'price',
            'label' => 'Price',
            'type' => 'number',
             'prefix' => "$",
             'suffix' => "USD",
        ]);
    }

    public function store(StoreRequest $request)
    {
        $redirect_location = parent::storeCrud($request);
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        $redirect_location = parent::updateCrud($request);
        return $redirect_location;
    }
}
