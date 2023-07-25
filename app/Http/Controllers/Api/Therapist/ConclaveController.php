<?php

namespace App\Http\Controllers\Api\Therapist;

use App\Http\Controllers\General\Controller;
use App\Http\Requests\Therapist\ConclaveRequest;
use App\Http\Resources\ConclaveResource;
use App\Http\Traits\HandleApi;
use App\Models\Conclave;
use App\Models\Rating;
use App\Models\User;
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

        if ($request->has('image')){
            $imagePath = Storage::disk('public')->put('conclave_images', $request->file('image'));
            $validatedData['image'] = $imagePath;
        }
        $conclave = Auth::user()->conclaves()->create($validatedData);

        //Rating
//        if ($request->has('rating')) {
//            Rating::create([
//                'conclave_id' => $conclave->id,
//                'user_id' => $request->user()->id,
//                'rating' => $request->input('rating')
//            ]);
//        }
        return $this->sendResponse(ConclaveResource::make($conclave) ,'Your Conclave has been created successfully');
    }
    // show own therapist conclaves
    public function ownConclaves()
    {
        $user = Auth::user();
        if (!$user || $user->status !== User::THERAPIST){
            return $this->sendError('Unauthorized', 'Please Make sure of your credentials');
        }
        $conclaves = $user->conclaves;
        return $this->sendResponse(ConclaveResource::collection($conclaves),'Your Conclaves fetched Successfully');
    }


    //by passing id in the body
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

    public function search(Request $request)
    {
        $query = $request->input('query');

        $conclaves = Conclave::where('name', 'like', "%$query%")->orWhereHas('user', function ($query) use ($query) {$query->where('name', 'like', "%$query%");})->get();

        return $this->sendResponse(ConclaveResource::collection($conclaves), 'Search results.');
    }


    public function update(ConclaveRequest $request, $id)
    {
        $conclave = Auth::user()->conclaves()->findOrFail($id);

        $validatedData = $request->validated();
        $conclave->update($validatedData);

        return $this->sendResponse(ConclaveResource::collection($conclave), 'Conclave updated successfully.');
    }

    public function destroy($id)
    {
        $conclave = Auth::user()->conclaves()->findOrFail($id);
        $conclave->delete();
        return $this->sendResponse([], 'Conclave deleted successfully.');
    }
}
