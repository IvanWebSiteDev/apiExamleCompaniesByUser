<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'data' => [
                'id' => $this->id,
                'type' => 'user',
                'attributes' => [
                    'first_name' => $this->first_name,
                    'last_name' => $this->last_name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                ], 'relationships' => [
                    'companies' => $this->when($this->companies->count() > 0, CompanyResource::collection($this->companies))
                ]
            ],
        ];
    }
}
