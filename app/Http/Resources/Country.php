<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Country extends JsonResource
{
    use TraitResource;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'ensign' => $this->images->first() ? asset(@$this->images->first()->url) : '',
            'cities' => $this->when($this->hasInclude($request, 'cities'), City::collection($this->cities)),
        ];
    }

    public function with($request)
    {
        return [
            'message' => 'Get country success!',
            'code' => 200,
        ];
    }

}
