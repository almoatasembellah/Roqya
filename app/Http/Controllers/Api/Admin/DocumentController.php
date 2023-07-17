<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\General\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DocumentController extends Controller
{

    public function index(Request $request)
    {
        $userId = $request->input('user_id');
        $documents = Document::where('user_id', $userId)->get();
        return response()->json($documents);
    }


    public function store(Request $request)
    {
        $request->validate([
//            'personal_pic' => 'required|image',
//            'national_id_pic' => 'required|image',
            'cv_file' => 'required|mimes:pdf',
        ]);

        $document = new Document();
        $document->user_id = Auth::id();

//        if ($request->hasFile('personal_pic')) {
//            $personalPicture = $request->file('personal_pic');
//            $personalPicturePath = $personalPicture->store('documents', 'public');
//            $document->personal_pic = $personalPicturePath;
//        }
//
//        if ($request->hasFile('national_id_pic')) {
//            $nationalIdPicture = $request->file('national_id_pic');
//            $nationalIdPicturePath = $nationalIdPicture->store('documents', 'public');
//            $document->national_id_pic = $nationalIdPicturePath;
//        }

        if ($request->hasFile('cv_file')) {
            $cvFile = $request->file('cv_file');
            $cvFilePath = $cvFile->store('documents', 'public');
            $document->cv_file = $cvFilePath;
        }

        $document->save();

        return response()->json($document, 201);
    }
}
