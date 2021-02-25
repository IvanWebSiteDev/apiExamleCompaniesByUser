<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Repositories\CompanyRepository;
use App\Repositories\CompanyRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var CompanyRepositoryInterface
     */
    private $company;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CompanyRepositoryInterface $company)
    {
        $this->middleware('auth');
        $this->company = $company;
        $this->user = Auth::user();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompanies(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['data' => ['type' => 'user', 'id' => $this->user->id,
            "relationships" => ['companies' => $this->company->getCompaniesByUser($this->user)]
        ]
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addCompanies(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'data.type' => 'required|in:user',
            'data.relationships.companies.data.type' => 'required|in:company',
            'data.relationships.companies.data.title' => 'required|max:350',
            'data.relationships.companies.data.phone' => 'required|max:50',
            'data.relationships.companies.data.description' => 'required|max:550'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => ['message' => 'Bad Request','detail'=>$validator->errors()]], 400);
        }

        $data = $request->only(['data.relationships.companies.data.title', 'data.relationships.companies.data.phone',
            'data.relationships.companies.data.description']);

        if ($company = $this->company->addCompany($this->user, $data['data']['relationships']['companies']['data'])) {

            $data['data']['relationships']['companies']['data']['id'] = $company->id;
            $data['data']['relationships']['companies']['data']['type'] = 'company';

            return response()->json(['meta' => ['message' => 'You add company: ' . $data['data']['relationships']['companies']['data']['title'],],
                'data' => ['id' => $this->user->id, 'type' => 'user', 'relationships' => $data['data']['relationships']
                ]], 201);
        } else {
            return response()->json(['error' => ['message' => 'Bad Request']], 400);
        }
    }
}
