<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Image;

class ScaleImage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scale:image';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make the lastest image scale';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Image $image)
    {
        $image_data=$image->orderBy('id','desc')->first();
        if(count($image_data->toArray())>0){
            $image_data->scale_image=app('resize')->AwsFolder('images')->size(100)->extension('png')->awsImage($image_data->name);
            $image_data->save();
            $this->info('Image Make Successfully'); // green
        }
       
    }
}
