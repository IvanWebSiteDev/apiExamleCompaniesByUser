<?php

namespace App\Http\Requests\User;


use App\Http\Requests\APIRequest;

class SigninRequest extends APIRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data.type' => 'required|in:user',
            'data.attributes.email' => 'required|email:rfc,dns|exists:users,email',
            'data.attributes.password' => 'required'
        ];
    }
}
