<?php


namespace App\Repositories;


use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param array $data
     * @return User
     */
    public function addUser(array $data)
    {
        $user = new User();
        foreach ($data as $column => $value) {
            $user->{$column} = $value;
        }
        $user->password = Hash::make($user->password);
        $user->save();

        return $user;
    }

    /**
     * @param string $email
     * @return User
     */
    public function findUserByEmail(string $email)
    {
        return User::with('companies')->where('email', $email)->first();
    }

    /**
     * @param User $user
     * @param string $password
     * @return false|string
     */
    public function checkUserPassword(User $user, string $password)
    {
        if (Hash::check($password, $user->password)) {
            $user->remember_token = $this->getHashPassword();
            $user->save();
            return $user->remember_token;
        }
        return $this->failedValidationPassword();
    }

    public function sendRessetToken(User $user)
    {
        $token = $this->getToken();
        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => \Carbon\Carbon::now()
        ]);

        return $token;
    }

    protected function failedValidationPassword()
    {
        throw new HttpResponseException(response()->json(['error' => ['message' => 'Bad Request', 'detail' => 'Wrong password']], 400));
    }

    private function getToken()
    {
        return Str::random(62);
    }
    private function getHashPassword()
    {
        return base64_encode(Str::random(40));
    }
}
