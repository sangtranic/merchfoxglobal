<?php

namespace App\Http\Controllers;
use Intervention\Image\Facades\Image as Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('imageUpload');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $imageName = time().'.'.$request->image->extension();

        $request->image->move(public_path('upload/original'), $imageName);


        //thumbnail
        if ($request->hasFile('photo')) {
            $image      = $request->file('photo');
            $img = Image::make($image->getRealPath());
            $img->resize(120, 120, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->stream(); // <-- Key point
            //dd();
            Storage::disk('local')->put('upload/thumbnail/'.$imageName, $img, 'public');
        }
        /*
            Write Code Here for
            Store $imageName name in DATABASE from HERE
        */

        return back()
            ->with('success','You have successfully upload image.')
            ->with('image',$imageName);
    }
}
