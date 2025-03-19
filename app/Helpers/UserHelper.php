<?php

namespace App\Helpers;

use App\Models\Internal\User;
use App\Repositories\Internal\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserHelper
{
    public static function validUser($email, $password)
    {
        $userRepository = new UserRepository(new User);
        $user = $userRepository->findByEmail($email);
        if (!Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['A senha informada está incorreta'],
            ]);
        }
    }

    public static function clearTokenReset($email)
    {
        DB::table('password_reset_tokens')->where('email', $email)->delete();
    }
}
