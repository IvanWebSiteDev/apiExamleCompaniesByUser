<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

abstract class APIRequest extends Request
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->validate();
    }

    /**
     * If validator fails return the exception in json form
     * @param Validator $validator
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['error' => ['message' => 'Bad Request','detail'=>$validator->errors()]], 400));
    }

    abstract public function rules();

    public function validate()
    {
        $validator = \Illuminate\Support\Facades\Validator::make($this->request->all(), $this->rules());
        if ($validator->fails()) {
            return $this->failedValidation($validator);
        }
        return $this->request;
    }
}
