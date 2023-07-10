<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UploadImageRequest;
use App\Http\Resources\UpdateProfileResource;
use App\Http\Resources\UserResource;
use App\Http\Traits\HandleApi;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Storage;

class ProfileController extends Controller
{
  use HandleApi;


    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return $this->sendError('Unauthorized', 'You are not logged in.');
        }
        return $this->sendResponse(new UserResource($user), 'Profile is fetched successfully.');
    }



    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update($request->validated());

        if ($request->has('profile_image')) {
            $user->profile_image = Storage::disk('public')->put('profile_images', $request->file('profile_image'));
            $user->save();
        }

        return $this->sendResponse(new UpdateProfileResource($user), 'Profile Data is changed Successfully');
    }


    public function uploadProfileImage(UploadImageRequest $request): JsonResponse
    {
        $imagePath = $request->file('profile_image')?->store('users', 'public');

        $request->user()->update([
            'profile_image' => $imagePath
        ]);
        return $this->sendResponse([], 'Profile Image is changed Successfully');
    }

    public function deleteProfileImage(Request $request)
    {
        $request->user()->update([
            'profile_image' => 'user.png'
        ]);

        return $this->sendResponse([], 'Profile Image is deleted Successfully');
    }



    public function destroy(string $id)
    {
        //
    }
}
