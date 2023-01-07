<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use \App\Traits\MyResponse;

class LoginRequest extends FormRequest
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
            'name' => 'min:5|required',
            'password' => 'required'
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "name is required",
            "name.min" => "name too small | min:5",
            "password.required" => "password is required",
        ];
    }
    public function failedValidation(ValidationValidator $validator)
    {
        //write your bussiness logic here otherwise it will give same old JSON response
        throw new HttpResponseException(MyResponse::returnError($validator->errors(), 422));
    }
    public function values()
    {
        return [
            $this->name,
            $this->password
        ];
    }
}
