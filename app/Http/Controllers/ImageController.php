<?php

namespace App\Http\Controllers;
use App\Repositories\Media\MediaRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use Intervention\Image\Facades\Image as Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    protected $mediaRepo;

    public function __construct(MediaRepositoryInterface $_mediaRepo)
    {
        $this->mediaRepo = $_mediaRepo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('imageUpload');
    }
    public function uploadimage(Request $request)
    {
        // Lấy ảnh từ request
        $image = $request->file('image');

        // Đặt tên file ảnh
        $filename = time() . '.' . $image->getClientOriginalExtension();

        // Lưu ảnh gốc vào thư mục 'images'
        $path = public_path('images/' . $filename);
        Image::make($image->getRealPath())->save($path);

        list($width, $height) = getimagesize($path);


        $newheight = (float)($width/300) * $height;
        // Tạo phiên bản ảnh với kích thước 300x300
        $thumbPath = public_path('images/thumbnail/' . $filename);
        Image::make($image->getRealPath())->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($thumbPath);
        //Image::make($image->getRealPath())->fit(300, $newheight)->save($thumbPath);

        $newheight = (float)($width/600) * $height;
        // Tạo phiên bản ảnh với kích thước 600x600
        $mediumPath = public_path('images/medium/' . $filename);
        Image::make($image->getRealPath())->resize(600, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($mediumPath);
        //Image::make($image->getRealPath())->fit(600, $newheight)->save($mediumPath);


        // Lưu tên file vào database hoặc thực hiện các thao tác khác
        return back()
            ->with('success','You have successfully upload image.')
            ->with('image',$filename);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Lấy ảnh từ request
        $image = $request->file('image');

        // Đặt tên file ảnh
        $filename = time() . '.' . $image->getClientOriginalExtension();

        // Lưu ảnh gốc vào thư mục 'images'
        $path = public_path('upload/original/' . $filename);
        Image::make($image->getRealPath())->save($path);

        // Tạo phiên bản ảnh với kích thước 300x300
        $thumbPath = public_path('upload/thumbnail/' . $filename);
        //Image::make($image->getRealPath())->fit(300, 300)->save($thumbPath);
        Image::make($image->getRealPath())->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($thumbPath);
        // Tạo phiên bản ảnh với kích thước 600x600
        $mediumPath = public_path('upload/medium/' . $filename);
        Image::make($image->getRealPath())->resize(600, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($mediumPath);
        //Image::make($image->getRealPath())->fit(600, 600)->save($mediumPath);
        $this->mediaRepo->create([
            'MediatypeId' => 1,
            'CrUserId' => 1,
            'MediaName' => $filename,
            'MediaDesc' => $filename,
            'FilePath' =>'upload/original/' . $filename
        ]);
        // Lưu tên file vào database hoặc thực hiện các thao tác khác
        return back()
            ->with('success','You have successfully upload image.')
            ->with('image',$filename);

//        $request->validate([
//            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//        ]);
//        $imageName = time().'.'.$request->image->extension();
//
//        $request->image->move(public_path('upload/original'), $imageName);
//
//
//        //thumbnail
//        if ($request->hasFile('photo')) {
//            $image      = $request->file('photo');
//            $img = Image::make($image->getRealPath());
//            $img->resize(120, 120, function ($constraint) {
//                $constraint->aspectRatio();
//            });
//            $img->stream(); // <-- Key point
//            //dd();
//            Storage::disk('local')->put('upload/thumbnail/'.$imageName, $img, 'public');
//        }
//        /*
//            Write Code Here for
//            Store $imageName name in DATABASE from HERE
//        */
//
//        return back()
//            ->with('success','You have successfully upload image.')
//            ->with('image',$imageName);
    }
}
