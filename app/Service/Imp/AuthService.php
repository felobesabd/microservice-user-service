<?php


namespace App\Service\Imp;

use App\Models\User;
use App\Redis\IPubSubPublisher;
use App\Repository\IUserRepo;
use App\Service\IAuthService;
use Firebase\JWT\JWT;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;

class AuthService implements IAuthService
{
    protected IUserRepo $userRepo;
    protected IPubSubPublisher $publisher;

    public function __construct(
       IUserRepo $userRepo,
       IPubSubPublisher $publisher
    ) {
        $this->userRepo = $userRepo;
        $this->publisher = $publisher;
    }

    public function register(array $data)
    {
        $user = $this->userRepo->createUser(
            $data['username'],
            $data['email'],
            $data['password']
        );

        $this->publisher->publisher('user_registered', [
            'type' => 'user_registered',
            'user_id' => $user->id,
            'username' => $user->username,
        ]);

        return [
            'user' => $user,
            'token' => $this->generateJwtToken($user)
        ];
    }

    public function login(array $data)
    {
        $user = $this->userRepo->getUserByEmail($data['email']);
        if (!$user &&  !is_object($user)) {
            return [
              'error' => 'invalid user please enter email again'
            ];
        }

        $checkPass = Hash::check($data['password'], $user->password);
        if (!$checkPass) {
            throw new HttpResponseException(response()->json([
                'status' => 'error',
                'message' => 'The given data was invalid.',
                'errors' => 'please enter correct password',
            ], 400));
        }

        return [
            'user' => $user,
            'token' => $this->generateJwtToken($user)
        ];
    }

    public function logout(array $data)
    {
        $user = User::find($data['id']);
        $user->delete();
    }

    private function generateJwtToken($user)
    {
        return $jwt = JWT::encode([
            'userId'=> $user->id,
            'username'=> $user->username,
            'expiredAt' => date("Y-m-d H:i:s", strtotime("+2 hour"))
        ], env('JWT_SECRET'), 'HS256');
    }

    public function updateCount($count, $user_id)
    {
        var_dump('felo 1');
        var_dump($count, $user_id);
        $user = $this->userRepo->getUserById($user_id);
        if ($user) {
            $user->update(['notes_count' => $count]);
        }

        var_dump('felo 2');
    }
}
