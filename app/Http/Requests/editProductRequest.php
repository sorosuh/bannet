<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class editProductRequest extends FormRequest
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
            'type' => '',
            'model' => '',
            'designed' => '',
            'brand' => '',
            'country' => '',
            'width' => '',
            'diameter' => '  numeric|min:1',
            'height' => 'numeric|min:1',
            'tire_height' => 'numeric|min:1',
            'color' => '',
            'weight' => ' numeric|min:1',
            'speed' => 'numeric|min:1',
            'tubeless' => ' numeric '

        ];
    }
}
