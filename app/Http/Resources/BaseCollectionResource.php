<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseCollectionResource extends ResourceCollection
{
    use TraitResource;

    protected $message = '';
    protected $status = '';

    public function with($request)
    {
        return [
            'message' => $this->message,
            'code' => $this->status,
        ];
    }

    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
}
