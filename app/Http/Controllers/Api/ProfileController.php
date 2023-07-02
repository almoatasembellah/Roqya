<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\HandleApi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
  use HandleApi;
    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }

    public function show(Request $request): JsonResponse
    {
        return $this->sendResponse(UserResource::make($request->user()), 'Profile is fetched successfully.');

    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $request->user()->update($request->validated());
        return $this->sendResponse([], 'Profile Data is changed Successfully');
    }

    public function destroy(string $id)
    {
        //
    }
}
