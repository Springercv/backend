<?php
namespace App\Services\Interfaces;

interface ImageServiceInterface
{
    public function collectAndSyncImages($model, $imagesUploads = [], $imagesPickup = [], $repositorySync);
    public function storageBase64AndCreateImage($value, $destination_path = '', $srcFolder = '', $disk = 'local', $attribute_name = "src");
    public function storageUploadFileImages($uploadFiles, $destination_path = '', $srcFolder = '', $disk = 'local');
    public function storageImageByUploadFile($file, $destination_path = '', $srcFolder = '', $disk = 'local');
    public function uploadImageByBase64($value, $destination_path = '', $srcFolder = '', $disk = 'local', $attribute_name = "src");
    public function deleteImageBySrc($src, $disk = 'local');
    public function replaceImageFile($searchSrc, $replaceSrc, $disk = 'local');
}