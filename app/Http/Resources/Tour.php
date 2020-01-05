<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Tour extends JsonResource
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
            'vehicle' => $this->vehicle,
            'hotel_type' => $this->hotel_type,
            'period_date' => $this->period_date,
            'schedule' => $this->schedule,
            'description' => $this->description,
            'note' => $this->note,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'prices' => @$this->prices->first()->price,
            'images' => @$this->images->map(function($image, $key) {return asset($image->url);})
        ];
    }

    public function with($request)
    {
        return [
            'message' => 'Get tours success!',
            'code' => 200,
        ];
    }
}
