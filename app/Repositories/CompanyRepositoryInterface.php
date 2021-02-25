<?php


namespace App\Repositories;


use App\Models\User;

interface CompanyRepositoryInterface
{
    public function getCompaniesByUser(User $user);
    public function addCompany(User $user,array $data);
}
