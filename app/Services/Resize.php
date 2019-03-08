<?php 

namespace App\Services;
use Illuminate\Support\Facades\Storage;
class Resize {

    var $size="320";
    public $upload_path='';
    var $extension='jpg'; 
    var $new_image='';
    var $image_name='';
    private $image_type_extension = ['jpg', 'JPG', 'png' ,'PNG' ,'jpeg' ,'JPEG']; // all images extension'
    private $aws_folder='uploads/';
    
  
    public function __construct(int $scale=0,string $upload_path='uploads'){
        if($scale>0){
            $this->size=$scale;
        }
        $this->upload_path=getcwd().'/public/'.$upload_path.'/';
    }

   
    private function scale(){
        exec('ffmpeg -i '.$this->upload_path.$this->image_name.' -vf scale='.$this->size.':-1 '.$this->newImage());
    }


     /**
     * save file in local storage
     * @param string 
     * @return object 
    */

    private function saveLocal(){
        $url = $this->image_name;
        $dir = public_path('/'.$this->aws_folder); // Full Path
        $name = time().'.jpg';
        is_dir($dir) || @mkdir($dir) || die("Can't Create folder");
        copy($url, $dir . DIRECTORY_SEPARATOR . $name);
        $this->image_name=$name;
        $this->upload_path=getcwd().'/public/'.$this->aws_folder.'/';
        $this->scale();
        return $this;
    }

    /**
     * Creating the new image name
     * @param string 
     * @return string 
    */

    private function newImage(){     
        if(strlen($this->new_image)==0){
            $this->new_name=$this->image_name;
            return $this->upload_path.$this->size.'_'.$this->image_name;
        }
        return $this->upload_path.$this->size.'_'.$this->new_image;
    }


    /**
     * Creating the new image name
     * @param string 
     * @return string 
    */

    private function newAwsImage(){     
        if(strlen($this->new_image)==0){
            $this->new_name=$this->image_name;
            return $this->upload_path.$this->size.'_'.$this->image_name;
        }
        return $this->upload_path.$this->size.'_'.$this->new_image;
    }


    /**
     * change the extension of the image
     * @param string 
     * @return object
    */

    public function extension(string $extension){
        if(!in_array($extension,$this->image_type_extension)) die("extension is wrong"); // check the extension is valid
        $this->new_image=time().'.'.$extension; // make the new image 
        return $this;
    }

    /**
     * make the size of the image
     * @param string 
     * @return object 
    */

    public function size(int $scale){
        $this->size=$scale;
        return $this;
    }

     /**
     * target the dir if 
     * @param string 
     * @return object 
    */
    public function UploadDir(string $folder){
        $this->upload_path=$folder.'/';
        return $this;
    }

    /**
     * target the dir if 
     * @param string 
     * @return object 
    */
    public function AwsFolder(string $folder){
        $this->aws_folder=$folder.'/';
        return $this;
    }

    /**
     * this function return the scaled image.
     * @param string 
     * @return string
     */

    public function makeImage(String $image_name){
        $this->image_name=$image_name;
        $this->scale();
        return $this->size.'_'.$this->new_image;
    }

    /**
     * this function save image in aws server s3 bukect
     * @param string 
     * @return void
     */
       
     private function awsSave(){
        $filePath = $this->aws_folder . $this->size.'_'.$this->new_image;
        $image_data = $this->upload_path . $this->size.'_'.$this->new_image;
        Storage::disk('s3')->put($filePath, file_get_contents($image_data),'public');
        unlink($image_data);
        unlink($this->upload_path .$this->image_name);
     }


      /**
     * this function store the image local and upload  image on aws.
     * @param string 
     * @return string
     */

    public function awsImage(String $image_name){
           $this->image_name=config('filesystems.disks.s3.url').$this->aws_folder.$image_name;
           $this->saveLocal();
           $this->awsSave();
           return $this->size.'_'.$this->new_image;
    }




}