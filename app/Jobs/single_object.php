<?php

namespace App\Jobs;

use App\helpers\utility;
use App\models\token_push\token_push_m;
use App\User;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Edujugon\PushNotification\PushNotification;

class single_object implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $object_data;


    public function __construct($object_data)
    {
        $this->object_data = $object_data;;
    }


    public function handle()
    {

        $namesArray  = [];
        $hitsArray  = [];

        if(isset($this->object_data['names']) && !empty($this->object_data['names'])){
            $namesArray = json_decode($this->object_data['names'], true);
        }
        if(isset($this->object_data['names']) && !empty($this->object_data['names'])){
            $hitsArray = json_decode($this->object_data['hits'], true);
        }



        foreach ($namesArray as $key=>$name_string)
        {
            $hits = isset($hitsArray[$key])?$hitsArray[$key]:0;

            echo "$name_string  $hits ";

            if(!empty($name_string)){

                dispatch(new single_name(
                    $name_string,
                    $hits
                ));

            }



//            $name_jobe = new single_name($name_string, $hits);
//            $name_jobe->handle();

        }



    }




}
