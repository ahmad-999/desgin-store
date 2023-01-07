<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use \App\Traits\MyResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UpdateDistributorRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => "required|exists:users,id",

        ];
    }
    public function messages()
    {
        return [
            "id.required" => "dist id is required",
            "id.exists" => "dist id must be in table users."

        ];
    }
    public function failedValidation(ValidationValidator $validator)
    {
        throw new HttpResponseException(MyResponse::returnError($validator->errors(), 422));
    }
    public function values()
    {
        return [
            'id' => $this->id,
            "name" => $this->name,
            "phone" => $this->phone,
            
        ];
    }
}
