<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Preference extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
            'image' => $this->images->first() ? asset(@$this->images->first()->url) : '',
        ];
    }

    public function with($request)
    {
        return [
            'message' => 'Get preference success!',
            'code' => 200,
        ];
    }
}
