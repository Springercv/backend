<?php
/**
 * Created by PhpStorm.
 * User: manhhd
 * Date: 3/8/19
 * Time: 2:29 PM
 */

namespace App\Http\Controllers\Admin;


use Backpack\CRUD\app\Http\Controllers\CrudController;

class BaseCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setListView('crud::customize.list');
        $this->crud->setCreateView('crud::customize.create');
        $this->crud->setEditView('crud::customize.edit');
        $this->crud->setShowView('crud::customize.show');
//        $this->crud->allowAccess('show');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
    }
}