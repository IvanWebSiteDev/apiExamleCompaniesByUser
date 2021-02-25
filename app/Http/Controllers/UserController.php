<?php

namespace App\Http\Controllers;

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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'data.type' => 'required|in:user',
            'data.attributes.first_name' => 'required|max:255',
            'data.attributes.last_name' => 'required|max:255',
            'data.attributes.email' => 'required|unique:users,email|email:rfc,dns|max:255',
            'data.attributes.password' => 'required|max:255',
            'data.attributes.phone' => 'required|unique:users,phone|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => ['message' => 'Bad Request','detail'=>$validator->errors()]], 400);
        }

        try {
            $data = $request->only(['data.attributes.first_name', 'data.attributes.last_name', 'data.attributes.email',
                'data.attributes.password', 'data.attributes.phone']);
            $user = $this->user->addUser($data['data']['attributes']);

            return response()
                ->json(['data' => ['id' => $user->id, 'type' => 'user','attributes' => $data['data']['attributes']]
                ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => ['message' => 'Bad Request']], 400);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function signIn(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'data.type' => 'required|in:user',
            'data.attributes.email' => 'required|email:rfc,dns|exists:users,email',
            'data.attributes.password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => ['message' => 'Bad Request','detail'=>$validator->errors()]], 400);
        }

        $data = $request->only(['data.attributes.email', 'data.attributes.password']);
        $user = $this->user->findUserByEmail($data['data']['attributes']['email']);

        if ($data['data']['attributes']['apikey'] = $this->user->checkUserPassword($user, $data['data']['attributes']['password'])) {
            unset($data['data']['attributes']['password']);
            return response()->json(
                ['data' => ['id' => $user->id, 'type' => 'user','attributes' => $data['data']['attributes']]], 200);
        } else {
            return response()->json(['error' => ['message' => 'Bad Request']], 400);
        }
    }

    /**
     * Allow to update the password via email token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function recoverPassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'data.type' => 'required|in:user',
            'data.attributes.email' => 'required|email:rfc,dns|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => ['message' => 'Bad Request','detail'=>$validator->errors()]], 400);
        }

        $data = $request->only(['data.attributes.email']);
        $user = $this->user->findUserByEmail($data['data']['attributes']['email']);
        $token = $this->user->sendRessetToken($user);

        return response()->json(['meta' => ['message' => ["We send token to your Email: $user->email"]],
            'data' => ['type' => 'user', 'id' => $user->id,'attributes' => ['email' => $user->email, 'token' => $token]]
        ], 201);
    }
}
