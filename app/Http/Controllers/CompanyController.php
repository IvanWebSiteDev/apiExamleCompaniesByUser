<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\AddCompanyRequest;
use App\Http\Resources\UserResource;
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
        return (UserResource::make($this->user->fresh('companies')))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * @param AddCompanyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCompanies(AddCompanyRequest $request): \Illuminate\Http\JsonResponse
    {
        $this->company->addCompany($this->user, $request->validated());

        return (UserResource::make($this->user->fresh('companies')))
            ->additional(['meta' => [
                'message' => 'You add company: ' . $request->validated()['title'],
            ]])
            ->response()
            ->setStatusCode(201);
    }
}
