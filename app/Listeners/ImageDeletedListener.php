<?php

namespace App\Listeners;

use App\Events\ImageDeleted;
use App\Services\Interfaces\ImageServiceInterface;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\ImageService;

class ImageDeletedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ImageServiceInterface $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ImageDeleted $event)
    {
        $image = $event->model;
        $this->imageService->deleteImageBySrc($image->src);
    }
}
