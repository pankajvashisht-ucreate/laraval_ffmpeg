<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Resize;

class FileUploadController extends Controller
{
    public function index(){
        return  view('index');
    }

    public function UploadFile(){
        $attribute=request()->validate([
            'image' => ['required','image']
        ]);
        $image_data  = $attribute['image'];
        $extension   = $image_data->getClientOriginalExtension();
        $image_name  = time().'.'.$extension;
        $destination_path    = public_path('/uploads');
        if($image_data->move($destination_path, $image_name)){
            $new_image=app('resize')->size(100)->makeImage($image_name);
            return back()->with('message','There is some issue!')->with('images',[
                'new_image' => $new_image,
                'image_name' => $image_name
            ]);
        }
        return back()->with('message','There is some issue!');
    }


}
