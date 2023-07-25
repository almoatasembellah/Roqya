<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\General\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Requests\User\UploadImageRequest;
use App\Http\Resources\UpdateProfileResource;
use App\Http\Resources\UserResource;
use App\Http\Traits\HandleApi;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            $defaultImagePath = 'img/default-profile-image.png';
            $request->user()->update([
                'profile_image' => $defaultImagePath
            ]);

            return $this->sendResponse([], 'Profile Image is deleted and reverted to the default Successfully');
        }

        public function changePassword(ChangePasswordRequest $request)
        {
            #Match The Old Password
            if (!Hash::check($request->get('old_password'), auth()->user()->password)) {
                return $this->sendError('Error', 'Old Password Doesn\'t match!');
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

    public function destroy(string $id)
    {
        //
    }
}
