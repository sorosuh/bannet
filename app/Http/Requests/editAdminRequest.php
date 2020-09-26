<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class editAdminRequest extends FormRequest
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
            'username' =>'nullable|string|min:3|max:255|unique:users',
            'password' =>'nullable|string|min:8',
            'phone' =>'nullable|Digits:10|unique:users',
        ];
    }

    public function messages()
    {
        return([
            'username.min'=>'نام کاربری باید حداقل شامل 3 کاراکتر باشد',
            'username.unique'=>'نام کاربری تکراری است',
            'password.min'=>'گدرواژه باید حداقل شامل 8 کاراکتر باشد',
            'phone.unique'=>'شماره تماس تکراری است',
            'phone.digits'=>'شماره تماس باید شامل 10 عدد باشد'
        ]);
    }
}
