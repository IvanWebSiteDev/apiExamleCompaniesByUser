<?php


namespace App\Repositories;


use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        return User::where('email', $email)->first();
    }

    /**
     * @param User $user
     * @param string $password
     * @return false|string
     */
    public function checkUserPassword(User $user, string $password)
    {
        if (Hash::check($password, $user->password)) {
            $apikey = base64_encode(Str::random(40));
            $user->remember_token = $apikey;
            $user->save();

            return $apikey;
        }else{
            return false;
        }
    }

    public function sendRessetToken(User $user)
    {
        $token = Str::random(62);
        DB::table('password_resets')->insert([
            'email'=>$user->email,
            'token'=> $token,
            'created_at'=> \Carbon\Carbon::now()
        ]);

        return $token;
    }

}
