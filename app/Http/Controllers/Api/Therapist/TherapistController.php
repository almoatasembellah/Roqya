<?php

namespace App\Http\Controllers\Api\Therapist;

use App\Http\Controllers\General\Controller;
use App\Http\Resources\UserResource;
use App\Http\Traits\HandleApi;
use App\Models\User;
use Illuminate\Http\Request;

class TherapistController extends Controller
{
    use HandleApi;
    public function therapistProfile(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->status !== User::THERAPIST)
             {
                    return $this->sendError('Unauthorized', 'You are not authorized to access this profile.');
                }

                return $this->sendResponse(UserResource::make($user), 'Therapist Profile fetched successfully.');
    }
}
