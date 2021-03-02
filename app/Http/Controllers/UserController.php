<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\RecoverPasswordRequest;
use App\Http\Requests\User\SigninRequest;
use App\Http\Requests\user\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepositoryInterface $user)
    {
        $this->user = $user;
    }

    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->user->addUser($request->validated());

        return (UserResource::make($user))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @param SigninRequest $signinRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function signIn(SigninRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->user->findUserByEmail(request('data.attributes.email'));
        $this->user->checkUserPassword($user, request('data.attributes.password'));

        return (UserResource::make($user))
            ->additional(['meta' => [
                'apikey' => $user->remember_token
            ]])
            ->response()
            ->setStatusCode(201);
    }


    /**
     * @param RecoverPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recoverPassword(RecoverPasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->user->findUserByEmail(request('data.attributes.email'));
        $token = $this->user->sendRessetToken($user);

        return (UserResource::make($user))
            ->additional(['meta' => [
                'message' => "We send token to your Email: $user->email",
                'token' => $token
            ]])
            ->response()
            ->setStatusCode(201);
    }
}
