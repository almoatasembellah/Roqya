<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\General\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            $findUser = User::where('facebook_id', $user->id)->first();

            if($findUser){
                Auth::login($findUser);
            }else{
                $newUser = User::updateOrCreate(['email' => $user->email],[
                   'name' => $user->name,
                    'facebook_id' => $user->id,
                    'password' => encrypt('123456dummy')
                ]);

                Auth::login($newUser);
            }
            return redirect()->intended('dashboard');
        }catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
