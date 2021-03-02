<?php


namespace App\Http\Requests\User;


class RecoverPasswordRequest extends \App\Http\Requests\APIRequest
{

    public function rules()
    {
        return [
            'data.type' => 'required|in:user',
            'data.attributes.email' => 'required|email:rfc,dns|exists:users,email',
        ];
    }
}
