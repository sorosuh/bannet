<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class adminRegisterRequest extends FormRequest
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
            'username' =>'required|string|min:3|max:255|unique:users',
            'password' =>'required|string|min:8',
            'phone' =>'required|Digits:10|unique:users',
        ];
    }

    public function messages()
    {
        return([
            'username.required'=>'فیلد نام کاربری الزامی است',
            'username.min'=>'نام کاربری باید حداقل شامل 3 کاراکتر باشد',
            'username.unique'=>'نام کاربری تکراری است',
            'password.required'=>'فیلد گذرواژه الزامی است',
            'password.min'=>'گدرواژه باید حداقل شامل 8 کاراکتر باشد',
            'phone.unique'=>'شماره تماس تکراری است',
            'phone.required'=>'فیلد شماره همراه الزامی است',
            'phone.digits'=>'شماره تماس باید شامل 10 عدد باشد'
        ]);
    }
}
