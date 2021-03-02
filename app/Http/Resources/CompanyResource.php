<?php


namespace App\Http\Resources;


class CompanyResource extends \Illuminate\Http\Resources\Json\JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => 'company',
            'attributes' => [
                'title' => $this->title,
                'phone' => $this->phone,
                'description' => $this->description,
            ]
        ];
    }
}
