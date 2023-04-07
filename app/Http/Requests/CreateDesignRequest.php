<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Http\Exceptions\HttpResponseException;
use \App\Traits\MyResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CreateDesignRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //'group_id' => 'required|exists:groups,id',
            'name' => 'required',
            // 'desc' => 'required',
            'image' => 'required',
            'tags' => 'required'
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "name is required",
            "group_id.required" => "group_id is required",
            "group_id.exists" => "group_id must be in groups table",
            "image.required" => "image is required",
            "desc.required" => "desc is required",
            "tags.required" => "tags is required",
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
            'group_id' => 1,
            'name' => $this->name,
            'desc' => $this->desc,
            "url" => ''
        ];
    }
}
