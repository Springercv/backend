<?php
namespace App\Repositories;

use ViralsBackpack\BackPackImageUpload\Models\Image;
use App\Repositories\Interfaces\ImageRepositoryInterface;
use Faker\Provider\Base;
use Illuminate\Support\Collection;


class ImageRepository extends BaseRepository implements ImageRepositoryInterface
{
    public function __construct()
    {
        $this->modelClass = Image::class;
    }
}