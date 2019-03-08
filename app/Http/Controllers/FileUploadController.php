<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Http\Requests\Fileupload;
use Illuminate\Support\Facades\Storage;


class FileUploadController extends Controller
{
    public function index(){
        return  view('index');
    }

    public function UploadFile(Fileupload $request){
        $attribute=$request;
        $image_data  = $attribute['image'];
        $extension   = $image_data->getClientOriginalExtension();
        $image_name  = time().'.'.$extension;
       // $destination_path    = public_path('/uploads');
        $filePath = 'images/' . $image_name;
        if(Storage::disk('s3')->put($filePath, file_get_contents($image_data),'public')){
            $add=[
                'name' => $image_name
            ];
            if(Image::create($add)){
                return back()->with('message','File uploaded successfully')->with('images',[
                    'image_name' => 'https://s3.ap-south-1.amazonaws.com/ucreate-demo-clone/images/'.$image_name
                ]);
            }
            return back()->with('message','Error to save file in db');
        }
        return back()->with('message','There is some issue!');
    }


}
