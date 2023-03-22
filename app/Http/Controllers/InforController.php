<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\InforResource;
use App\Http\Resources\ResponseResource;
use App\Models\Infor;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;


class InforController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Infor::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function resizeImage($image)
    {
        $imagePath = $image->store('public/images');
        $imageFullPath = storage_path('app/' . $imagePath);

        $resizeImageInfor = Image::make($image);
        $imageWidth = $resizeImageInfor->getWidth();
        $imageHeight = $resizeImageInfor->getHeight();

        $newWidth = $imageWidth * 0.4;
        $newHeight = $imageHeight * 0.4;

        $resizedImage = Image::make($imageFullPath)
            ->resize($newWidth, $newHeight)
            ->save($imageFullPath);

        return $imagePath;
    }

    public function uploadImage(Request $request)
    {
        $image = $request->file('image');
        $imagePath = $this->resizeImage($image);

        return response()->json([
            'message' => 'Image resized successfully',
            'image_path' => Storage::url($imagePath),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $image = $request->file('image');
        $imagePath = $this->resizeImage($image);


        return response()->json([
            'message' => 'Image resized successfully',
            'image_path' => Storage::url($imagePath),
        ]);
    }



    /**
     * Display the specified resource.
     */
    public function show($infor)
    {
        $_infor = Infor::find($infor);

        return new ResponseResource($_infor);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Infor $infor)
    {

        $infor->update($request->all());
        $infor->save();

        return new ResponseResource($infor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
