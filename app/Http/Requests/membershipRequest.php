<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class membershipRequest extends FormRequest
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
            'title' => 'required | string',
            'price' => 'required |  numeric|min:0',
            'unity' => 'required |  numeric|min:0',
            'revival' => 'required |  numeric|min:0',
            'dmg_level_one' => 'required |  numeric|min:0',
            'dmg_level_two' => 'required |  numeric|min:0|max:100'
        ];
    }

    public function messages()
    {
        return ([
            'title.required' => 'فیلد  "عنوان"  اجباری است',
            'price.required' => 'فیلد "قیمت" اجباری است',
            'unity.required' => 'فیلد "ابونمان" اجباری است',
            'revival.required' => 'فیلد" هزینه تمدید" اجباری است',
            'dmg_level_one.required' => 'فیلد "مبلغ خسارت درجه یک" اجباری است',
            'dmg_level_two.required' => 'فیلد "مبلغ خسارت درجه دو" اجباری است'
        ]);
    }
}
