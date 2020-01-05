<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\MasterSearchAttributeStoreRequest as StoreRequest;
use App\Http\Requests\MasterSearchAttributeUpdateRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class Master_search_attributeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MasterSearchAttributeCrudController extends BaseCrudController
{
    public function setup()
    {
        parent::setup();
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\MasterSearchAttribute');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/master_search_attribute');
        $this->crud->setEntityNameStrings('master search attribute', 'master search attributes');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();
        $this->setupColumns();
        $this->setupFields();

        // add asterisk for fields that are required in Master_search_attributeRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    private function setupColumns()
    {
        $this->crud->addColumn([
           'name' => 'key',
           'type' => 'text',
           'label' => 'Key',
        ]);

        $this->crud->addColumn([
            'name' => 'value',
            'type' => 'text',
            'label' => 'Value',
            'limit' => 500,
        ]);
    }

    private function setupFields()
    {
        $this->crud->addField([ // Text
            'name' => 'key',
            'label' => "<span style='color: red'>Key:</span>",
            'type' => 'text'
        ]);
        $this->crud->addField([ // Text
            'name' => 'value',
            'label' => "<span style='color: red'>Value:</span>",
            'type' => 'text'
        ]);

//        $tables = \DB::connection()->getDoctrineSchemaManager()->listTableNames();
//        foreach ($tables as $table) {
//            $columns = \DB::connection()->getSchemaBuilder()->getColumnListing($table);
//            $data = [];
//            foreach ($columns as $column) {
//                $data[$column] = $column;
//            }
//            $this->crud->addField([ // select_from_array
//                'name' => "data[" . getModelByTablename($table) . "]",
//                'label' => "<span style='color: #1a32da'>" . $table . "</span> :",
//                'type' => 'select2_from_array',
//                'options' => $data,
//                'allows_null' => true,
//                'allows_multiple' => true,
//            ]);
//        }

    }

    public function store(StoreRequest $request)
    {
        $tables = $request->data ?? [];
        $value = '';
        foreach ($tables as $table => $columns) {
            $value .= $table . ':' . implode(',', $columns);
            $value .= next ( $tables ) ? ';' : '';
        }
        $value = !is_null($request->value) ? $request->value : $value;
        $request->merge(['value' => $value]);
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
}
