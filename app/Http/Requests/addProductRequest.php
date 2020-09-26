<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required| string',
            'model' => 'required| string',
            'designed' => 'required| string',
            'brand' => 'required| string',
            'country' => 'required | string',
            'width' => 'required |numeric|min:1',
            'diameter' => 'required | numeric|min:1',
            'height' => 'required | numeric|min:1',
            'tire_height' => 'required | numeric|min:1',
            'color' => 'required| string',
            'weight' => 'required | numeric|min:1',
            'speed' => 'required | numeric|min:1',
            'tubeless' => 'required | numeric '

        ];
    }
}
