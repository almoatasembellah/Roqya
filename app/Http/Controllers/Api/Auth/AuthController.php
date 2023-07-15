<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Traits\HandleApi;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HandleApi;

    public function register(RegisterRequest $request): JsonResponse
    {
        $input = $request->validate($request->rules());

        $input['password'] = Hash::make($input['password']);

        $input['profile_image'] = 'https://upload.wikimedia.org/wikipedia/commons/8/89/Portrait_Placeholder.png';

        $user = User::create($input);

        $data['token'] =  $user->createToken('Roqya')->plainTextToken;

        $data['name'] =  $user->name;

        return $this->sendResponse($data, 'You register successfully.');

    }

    public function userLogin(LoginRequest $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password'), 'status' => User::USER])) {
            $user = Auth::user();

            $data['token'] = $user?->createToken('Roqya')->plainTextToken;

            $data['name'] = $user?->name;

            return $this->sendResponse($data, 'You\'ve logged in successfully.');
        }

        return $this->sendError('Unauthorized','This email or password is wrong for user.');
    }


        public function therapistLogin(LoginRequest $request): JsonResponse
        {
            if(Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password'), 'status' => User::THERAPIST])) {
                $user = Auth::user();

                $data['token'] = $user?->createToken('Roqya')->plainTextToken;

                $data['name'] = $user?->name;

                return $this->sendResponse($data, 'You\'ve logged in successfully Therapist.');
            }

            return $this->sendError('Unauthorized','This email or password is wrong for therapist.');
        }


        public function changePassword(ChangePasswordRequest $request)
        {
            #Match The Old Password
            if (!Hash::check($request->get('old_password'), auth()->user()->password)) {
                return $this->sendError('Error', 'Old Password Doesn\'t match!', 400);
            }

        #Update the new Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->get('new_password'))
        ]);

        return $this->sendResponse([], 'Password changed successfully!');
        }

        public function logout(Request $request): JsonResponse
        {
            $request->user()->tokens()->delete();
            return $this->sendResponse([], 'You have logged out Successfully');
        }

        //Google Auth
//    public function googleauth(Request $request)
//    {
//        $rules = [
//            "device_id" => ['required'],
//            //"device_token" => ['required'],
//
//            "provider_id" => [Rule::requiredIf($request->phone == null), 'numeric'],
//            "provider_name" => [Rule::requiredIf($request->provider_id != null), 'string', 'in:facebook,google'],
//            "name" => [Rule::requiredIf($request->provider_id != null && !User::where('provider_id', $request->provider_id)->where('provider_name', $request->provider_name)->exists()), 'min:3','max:20'],
//            "email" => ['required', 'email'],
//            "profile_image" => Rule::requiredIf($request->provider_id != null && !User::where('provider_id', $request->provider_id)->where('provider_name', $request->provider_name)->exists()),
//
//            "uid" => Rule::requiredIf($request->provider_id == null && $request->phone == null),
//            "password" => Rule::requiredIf($request->uid != null),
//
//            "phone" => Rule::requiredIf($request->uid == null && $request->provider_id == null),
//
//            "country_code" => ['required', 'min:2', 'max:2'],
//        ];
//
//        $validator = Validator::make($request->all(), $rules);
//        if($validator->fails()) {
//            return $this->validationError(422, 'The given data was invalid.', $validator);
//        }
//
//        if($request->provider_name !== null){
//
//            try{
//                $user = User::firstOrCreate(
//                    ['provider_id' => $request->provider_id, 'provider_name' => $request->provider_name],
//
//                    [
//                        'provider_id' => $request->provider_id,
//                        'provider_name' => $request->provider_name,
//                        'name' => $request->name,
//                        'email' => $request->email,
//                        'profile_picture' => $request->profile_image,
//                        'device_id' => $request->device_id,
//                        //'device_token' => $request->device_token,
//                        //'password' => $this->generateDefaultPassword(),
//                        'uid' => $this->generateUID(),
//                        'phone' => $request->phone,
//                        'country_code' => $request->country_code,
//                        'level_id' => 1,
//                    ],
//                );
//            }catch(QueryException $e){
//                // return $e;
//                return $this->error500();
//            }
//        }elseif($request->phone !== null){
//            try{
//
//                $user = User::firstOrCreate(
//                    ['phone' => $request->phone, 'device_id' => $request->device_id],
//
//                    [
//                        'provider_id' => $request->provider_id,
//                        'provider_name' => $request->provider_name,
//                        'name' => $request->name,
//                        'email' => $request->email,
//                        'profile_picture' => $request->profile_image,
//                        'device_id' => $request->device_id,
//                        //'device_token' => $request->device_token,
//                        //'password' => $this->generateDefaultPassword(),
//                        'uid' => $this->generateUID(),
//                        'phone' => $request->phone,
//                        'country_code' => $request->country_code,
//                        'level_id' => 1,
//                    ],
//                );
//            }catch(QueryException $e){
//                // return $e;
//                return $this->error500();
//            }
//        }elseif($request->uid !== null){
//            $userObj = User::where('uid', $request->uid);
//
//            if(!$userObj->exists()){
//                return $this->error('عضو غير مسجل', 403);
//            }
//
//            $userObj = $userObj->first();
//            if(Hash::check($request->password, $userObj->password)){
//                $user = $userObj;
//            }else{
//                return $this->error('كلمة المرور خاطئة', 403);
//            }
//        }
//
//        /* Check if user is blocked */
//        $today = Carbon::today();
//        if($user->deactivated_until >= $today){
//            if($user->deactivated_until === '1-1-3099'){
//                return $this->error('عفوا، هذا الحساب محظور بشكل دائم.', 401);
//            }
//            return $this->error('عفوا، هذا الحساب محظور حتى تاريخ: '.$user->deactivated_until, 401);
//        }
//
//
//        $token = auth()->login($user);
//
//        //Handle First Time login to continue info with phone Register
//        $firstTime = false;
//        if($user->created_at == now()){
//            $firstTime = true;
//        }
//
//        $data = ['token' => $token, 'first_time' => $firstTime];
//        return $this->data($data, 'Hello Buddy :)');
//    }
}
