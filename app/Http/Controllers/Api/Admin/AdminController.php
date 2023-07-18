<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\General\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\HandleApi;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    use HandleApi;

    public function adminLogin(LoginRequest $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password'), 'status' => User::ADMIN])) {
            $user = Auth::user();

            $data['token'] = $user?->createToken('Roqya')->plainTextToken;

            $data['name'] = $user?->name;

            return $this->sendResponse($data, 'You\'ve logged in successfully Admin.');
        }

        return $this->sendError('Unauthorized','This email or password is wrong for admin.');
    }


    public function adminLogout(Request $request): JsonResponse
    {
        if (Auth::user()->status == User::ADMIN) {
            $request->user()->tokens()->delete();
            return $this->sendResponse([], 'You have logged out successfully as an admin.');
        }

        return $this->sendError('Unauthorized', 'You are not authorized to perform this action.');
    }


    public function getAllUsers(Request $request)
    {
        if ($request->user()->status == User::ADMIN) {
            $users = User::paginate(10);
            return $this->sendResponse(UserResource::collection($users), 'All Users are Fetched');
        }

        return $this->sendError('Unauthorized', 'You are not authorized to perform this action.');
    }


    public function changeStatus(Request $request)
    {
        $user = User::findOrFail($request->input('id'));

        if (Auth::user()->status === User::ADMIN && $user->id === Auth::user()->id) {
            return $this->sendError('error', 'You cannot change your own status.');
        }

        if (Auth::user()->status != User::ADMIN){
            return $this->sendError('error','You\'re not authorized to perform this action');
        }

        $this->validate($request,[
           'status' => 'required|integer|in:' . User::THERAPIST . ',' . User::USER,
        ]);

        $user->status = $request->input('status');
        $user->save();

        return $this->sendResponse([],'Status changed successfully');
    }
}
