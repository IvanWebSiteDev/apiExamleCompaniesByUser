<?php


namespace App\Http\Requests\User;


use App\Http\Requests\APIRequest;

class RegisterRequest extends APIRequest
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
            'data.attributes.first_name' => 'required|max:255',
            'data.attributes.last_name' => 'required|max:255',
            'data.attributes.email' => 'required|unique:users,email|email:rfc,dns|max:255',
            'data.attributes.password' => 'required|max:255',
            'data.attributes.phone' => 'required|unique:users,phone|max:50'
        ];
    }

    public function validated(): array
    {
        return $this->request->all()['data']['attributes'];
    }
}
