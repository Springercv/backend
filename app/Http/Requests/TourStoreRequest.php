<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class TourStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:tours,name',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:today|after:start_date',
            'vehicle' => 'required',
            'hotel_type' => 'required',
            'period_date' => 'required|numeric|min:1',
            'schedule' => 'required',
            'description' => 'required',
            'note' => 'required',
            'country_id' => 'required',
            'preferences' => 'required',
            'prices' => 'required',

        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
