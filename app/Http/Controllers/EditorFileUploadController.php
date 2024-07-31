<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EditorFileUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileUpload(Request $request)
    {
        $file = $request->file('file');
        $destinationPath = public_path().'/uploads/content';
        $fileName = $file->getClientOriginalName();
        $originalName = strtolower(trim($fileName));
        $fileName = time().rand(100, 999).$originalName;
        $file->move($destinationPath, $fileName);

        return url('/uploads/content/'.$fileName);
    }
}
