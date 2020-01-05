<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ImageStoreRequest as StoreRequest;
use Backpack\CRUD\CrudPanel;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Services\Interfaces\ImageServiceInterface;

/**
 * Class ImageCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ImageCrudController extends BaseCrudController
{
    public function __construct(ImageServiceInterface $imageService)
    {
        parent::__construct();
        $this->imageService = $imageService;
    }

    public function setup()
    {
        parent::setup();
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('ViralsBackpack\BackPackImageUpload\Models\Image');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/image');
        $this->crud->setEntityNameStrings('image', 'images');
        $this->crud->removeButton('update');
        $this->crud->noExtraSave = true;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->setupColumns();
        $this->setupFields();

        // add asterisk for fields that are required in ImageRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
    }

    private function setupColumns()
    {
        //------------------Column---------------------//
        $this->crud->addColumn([
            'name' => 'url', // The db column name
            'label' => "Image", // Table column heading
            'type' => 'image',
            //'prefix' => 'upload/subfolder/',
            // optional width/height if 25px is not ok with you
            'height' => '50px',
            // 'width' => '30px',
        ]);
    }

    private function setupFields()
    {
        //------------------Field---------------------//
        $this->crud->addField([
            'label' => "Image",
            'name' => 'url',
            'type' => 'image_mutil_upload_custom',
            'upload' => true
        ]);
    }
    public function store(StoreRequest $request)
    {
        $validation  = $this->imageService->validateImage($request->file('image_uploads'));
        if ($validation['status_code'] == 500) {
            \Alert::error($validation['message'])->flash();
            return redirect()->back();
        }
        $this->imageService->storageUploadFileImages($request->file('image_uploads'));
         \Alert::success('Upload successful.')->flash();
        $content = parent::index();
        return $content;
    }

    public function update(UpdateRequest $request)
    {
        $redirect_location = parent::updateCrud($request);
        return $redirect_location;
    }
}
