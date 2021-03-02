<?php


namespace App\Http\Requests\Company;


class AddCompanyRequest extends \App\Http\Requests\APIRequest
{

    public function rules()
    {
        return [
            'data.type' => 'required|in:user',
            'data.relationships.companies.data.type' => 'required|in:company',
            'data.relationships.companies.data.title' => 'required|max:350',
            'data.relationships.companies.data.phone' => 'required|max:50',
            'data.relationships.companies.data.description' => 'required|max:550'
        ];
    }

    public function validated(): array
    {
        return $this->request->except(['data.relationships.companies.data.type'])['data']['relationships']['companies']['data'];
    }
}
