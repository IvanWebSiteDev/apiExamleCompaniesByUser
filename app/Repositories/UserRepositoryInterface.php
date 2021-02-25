<?php


namespace App\Repositories;


use App\Models\User;

interface UserRepositoryInterface
{
    public function addUser(array $data);
    public function findUserByEmail(string $email);
    public function checkUserPassword(User $user,string $password);
    public function sendRessetToken(User $user);
}
