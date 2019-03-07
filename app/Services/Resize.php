<?php 

namespace App\Services;

class Resize {

    var $size="320";
    public $upload_path='';
    var $extension='jpg'; 
    var $new_image='';
    var $image_name='';
    private $image_type_extension = ['jpg', 'JPG', 'png' ,'PNG' ,'jpeg' ,'JPEG']; // all images extension
    
  
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
     * this function return the scaled image.
     * @param string 
     * @return string
     */

    public function makeImage(String $image_name){
        $this->image_name=$image_name;
        $this->scale();
        return $this->size.'_'.$this->new_image;
    }


}