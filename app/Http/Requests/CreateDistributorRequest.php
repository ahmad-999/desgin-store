<?php

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use \App\Traits\MyResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CreateDistributorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    public function rules()
    {
        return [
            'name' => 'min:5|required|unique:users,name',
            'password' => 'required',

        ];
    }
    public function messages()
    {
        return [
            "name.required" => "name is required",
            "name.unique" => "name must be unique",
            "name.min" => "name too small | min:5",
            "password.required" => "password is required",
        ];
    }
    public function failedValidation(ValidationValidator $validator)
    {
        //write your bussiness logic here otherwise it will give same old JSON response
        $errors = $validator->errors();
        $es = [];
        foreach($errors->getMessages() as $key => $error){
            array_push($es,[
                "key" => $key,
                "errors" => $error
            ]);
        }
        throw new HttpResponseException(MyResponse::returnError($es, 422));
    }
    public function values()
    {
        return [
            'name' => $this->name,
            'password' => Hash::make($this->password),
            'type' => 'distributor',
            'phone' => $this->phone,
            'avatar_url' => $this->avatar_url,
        ];
    }
}
