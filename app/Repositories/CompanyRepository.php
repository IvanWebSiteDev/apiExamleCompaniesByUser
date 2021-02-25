<?php


namespace App\Repositories;


use App\Models\Company;
use App\Models\User;

class CompanyRepository implements CompanyRepositoryInterface
{
    public function getCompaniesByUser(User $user)
    {
        return Company::where('user_id', $user->id)
            ->get(['id', 'title', 'phone', 'description'])
            ->map(function ($company){
                $company->type = 'company';
                return $company;
            });
    }

    public function addCompany(User $user, array $data)
    {
        $company = new Company();
        foreach ($data as $column => $value) {
            $company->{$column} = $value;
        }
        $company->user_id = $user->id;
        $company->save();

        return $company;
    }
}
