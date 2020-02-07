<?php

namespace App\Jobs;

use App\helpers\utility;
use App\models\token_push\token_push_m;
use App\Name;
use App\User;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Edujugon\PushNotification\PushNotification;

class upload_file implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $file_path;


    public function __construct($file_path)
    {
        $this->file_path = $file_path;;
    }


    public function handle()
    {


        $jsonString = file_get_contents($this->file_path);

        $data = json_decode($jsonString, true);

        echo "herre  ";

        if(count($data)){
            foreach ($data as $object){

                dispatch(new single_object(
                    $object
                ));



//                $single_object =  new single_object($object);
//
//                $single_object->handle();

            }

        }


    }




}
