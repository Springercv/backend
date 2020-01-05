<?php
namespace App\Services;

use App\Repositories\Interfaces\ImageRepositoryInterface;
use App\Services\Interfaces\ImageServiceInterface;
use Illuminate\Http\UploadedFile;

class ImageService implements ImageServiceInterface
{
    public function __construct(ImageRepositoryInterface $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    /**
     * collect upload and pickup image, create and sync with image table
     * @param $model
     * @param array $imagesUpload
     * @param array $imagesPickup
     * @param $repositorySync
     */
    public function collectAndSyncImages($model, $imagesUploads = [], $imagesPickup = [], $repositorySync)
    {
        // create image upload
        $imageUploadIds = $this->storageUploadFileImages($imagesUploads);

        //merge image upload and pickup
        $imageIds = array_merge($imageUploadIds, $imagesPickup);

        // sync image pickup
        $repositorySync->syncImages($model, $imageIds);
    }

    public function storageBase64AndCreateImage($value, $destination_path = '', $srcFolder = '', $disk = 'local', $attribute_name = "url", $data = [])
    {
        $src = $this->uploadImageByBase64($value, $destination_path, $srcFolder, $disk, $attribute_name);
        $data['src'] = $src;
        return $this->imageRepository->createModel($data);
    }

    /**
     * storage and create image from file upload
     * @param $uploadFiles
     * @param string $destination_path
     * @param string $srcFolder
     * @param string $disk
     * @return mixed
     */
    public function storageUploadFileImages($uploadFiles, $destination_path = '', $srcFolder = '', $disk = 'local')
    {
        if (!($uploadFiles instanceof Collection)) {
            $uploadFiles = collect($uploadFiles);
        }
        return $uploadFiles->map(function($file, $index) use ($disk, $destination_path, $srcFolder) {
            //1.storage image
            $src = $this->storageImageByUploadFile($file, $destination_path, $srcFolder, $disk);
            //2.create image record
            $this->imageRepository->createModel(['url' => $src]);
            return $this->imageRepository->findByAttrFirst('url', $src);
        })->pluck('id')->toArray();
    }

    /**
     * storage image from UploadFile object
     * @param $file
     * @param string $destination_path
     * @param string $srcFolder
     * @param string $disk
     * @return string
     */
    public function storageImageByUploadFile($file, $destination_path = '', $srcFolder = '', $disk = 'local')
    {
        $destination_path = DESTINATION_FOLDER_UPLOAD_IMAGE . $destination_path;
        $srcFolder = SOURCE_FOLDER_UPLOAD_IMAGE . $srcFolder;

        $extension = $file->getClientOriginalExtension();
        $filename = date('mdYHis') . uniqid() . '.'. $extension;
        \Storage::disk($disk)->put($destination_path .'/'. $filename, \File::get($file));
        return $srcFolder . $filename;
    }

    /**
     * storage image from base64
     * @param $value
     * @param string $destination_path
     * @param string $srcFolder
     * @param string $disk
     * @param string $attribute_name
     * @return string
     */

    public function uploadImageByBase64($value, $destination_path = '', $srcFolder = '', $disk = 'local', $attribute_name = "src")
    {
        $destination_path = DESTINATION_FOLDER_UPLOAD_IMAGE . $destination_path;
        $srcFolder = SOURCE_FOLDER_UPLOAD_IMAGE . $srcFolder;

        if (starts_with($value, 'data:image'))
        {
            $image = \Image::make($value)->encode('jpg', 90);
            $filename = date('mdYHis') . uniqid() . '.jpg';
            \Storage::disk($disk)->put($destination_path .'/'. $filename, $image->stream());
            $src = $srcFolder . $filename;
            return $src;
        }
    }

    /**
     * delete image file from src
     * @param $src
     * @param string $disk
     */
    public function deleteImageBySrc($src, $disk = 'local')
    {
        $this->imageRepository->deleteByAttr('url', $src); //delete record in image table
        $src = str_replace(SOURCE_FOLDER_UPLOAD_IMAGE,DESTINATION_FOLDER_UPLOAD_IMAGE, $src);
        \Storage::disk($disk)->delete($src); //delete image file
    }

    /**
     * upload and replace image file
     * @param $searchSrc
     * @param $replaceSrc
     * @param string $disk
     * @return bool|string
     */
    public function replaceImageFile($searchSrc, $replaceSrc, $disk = 'local', $dataImage = [])
    {
        if (starts_with($replaceSrc, 'data:image')) {
            $this->deleteImageBySrc($searchSrc);
            $src = $this->uploadImageByBase64($replaceSrc, '', '', $disk); // $replaceSrc is base64
            $dataImage['src'] = $src;
            return $this->imageRepository->createModel($dataImage);
        }

        if ($replaceSrc instanceof UploadedFile) {
            $this->deleteImageBySrc($searchSrc);
            return $this->storageImageByUploadFile($replaceSrc, '', '', $disk); // $replaceSrc is UploadFile object
        }

        return false;
    }


    public function validateImage($files)
    {
        if (!is_null($files)) {
            $files = is_array($files) ? $files : [$files];
            $allowedfileExtension=['jpg','png'];
            $totalSize = 0;
            foreach($files as $file) {
                $extension = $file->getClientOriginalExtension();
                $checkExtension = in_array($extension, $allowedfileExtension);
                $totalSize += $file->getSize();
                if (!$checkExtension) {
                    return [
                        'status_code' => 500,
                        'message' => 'File upload is not image type',
                    ];
                }
            }
            if ($totalSize >= 10*1024*1024) {
                return [
                    'status_code' => 500,
                    'message' => 'Size of File Upload are too big, must < 10M',
                ];
            }
        }
        return [
            'status_code' => 200,
            'message' => 'Validate image success',
        ];
    }
}