<?php

namespace App\Http\Controllers\Api\Therapist;

use App\Http\Controllers\General\Controller;
use App\Http\Requests\Therapist\ConclaveRequest;
use App\Http\Resources\ConclaveResource;
use App\Http\Traits\HandleApi;
use App\Models\Conclave;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Storage;

class ConclaveController extends Controller
{
    use HandleApi;

    public function index(){
        $conclaves = Conclave::all();
        return $this->sendResponse(ConclaveResource::collection($conclaves), 'Conclaves are fetched successfully.');
    }

    public function store(ConclaveRequest $request)
    {
        $validatedData = $request->validated();

        //image
        if ($request->has('image')){
            $imagePath = Storage::disk('public')->put('conclave_images', $request->file('image'));
            $validatedData['image'] = $imagePath;
        }
        $conclave = Auth::user()->conclaves()->create($validatedData);

        //Rating
        if ($request->has('rating')) {
            Rating::create([
                'conclave_id' => $conclave->id,
                'user_id' => $request->user()->id,
                'rating' => $request->input('rating')
            ]);
        }

        return $this->sendResponse(ConclaveResource::make($conclave) ,'Your Conclave has been created successfully');
    }

    public function getTherapistConclaves (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Missing user_id','Enter user_id to body');
        }
        $user_id = $request->input('user_id');
        $conclaves = Conclave::where('user_id', $user_id)->get();
        return $this->sendResponse(ConclaveResource::collection($conclaves),'Your Conclaves are fetched only');
    }


    public function rateConclave(Request $request, Conclave $conclave)
    {
        $user = $request->user();

        // Check if the user is the participant of the conclave
        if (!$conclave->users->contains($user->id)) {
            return response()->json(['message' => 'You are not authorized to rate this conclave'], 403);
        }

        // Check if the user has already rated this conclave
        if ($conclave->ratings->where('user_id', $user->id)->count() > 0) {
            return response()->json(['message' => 'You have already rated this conclave'], 400);
        }

        // Validate the user's rating input
        $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

        // Create the new rating
        $rating = new Rating([
            'user_id' => $user->id,
            'conclave_id' => $conclave->id,
            'rating' => $request->input('rating'),
        ]);
        $rating->save();

        // Calculate the overall rating for the therapist
        $this->calculateOverallRating($conclave);

        return response()->json(['message' => 'Conclave rated successfully']);
    }



    //Overall Rating
    public function calculateOverallRating(Conclave $conclave)
    {
        $ratings = $conclave->ratings;

        if ($ratings->count() === 0) {
            return null;
        }

        $totalRating = $ratings->sum('rating');
        $averageRating = $totalRating / $ratings->count();

        // Update therapist's overall_rating
        $conclave->therapist->update(['overall_rating' => $averageRating]);

        return $averageRating;
    }
}
