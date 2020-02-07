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

class send_push implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $usersTokens;
    public $data;
    public $offset;
    public $limit;
    public $recursion;
    public $device_type;

    public function __construct($usersTokens=[],$data,$offset=0,$limit=0,$recursion = false,$device_type='')
    {
        $this->usersTokens = $usersTokens;
        $this->data   = $data;
        $this->offset = $offset;
        $this->limit  = $limit;
        $this->recursion = $recursion;
        $this->device_type = $device_type ;
    }


    public function handle()
    {

        $allData = collect($this->usersTokens);


        $tokens =  $allData->groupBy('device_type');


        $android = isset($tokens['android'])?$tokens['android']->pluck('token_mobile_push'):[];
        $ios = isset($tokens['ios'])?$tokens['ios']->pluck('token_mobile_push'):[];


        if (count($android)>0)

        {
            $pushAndroid  = new PushNotification('fcm');

            $pushAndroid->setMessage([
                'notification' => [
                    'title'=>isset($this->data['title'])?$this->data['title']:'',
                    'body'=>isset($this->data['body'])?$this->data['body']:'',
                    'sound' => isset($this->data['sound'])?$this->data['sound']:''
                ],
                'data' =>  isset($this->data['addition_data'])?$this->data['addition_data']:[]
            ]);
            $pushAndroid->setApiKey('');
            $pushAndroid->setDevicesToken($android->toArray());
            $pushAndroid->send()->getFeedback();
            $androidUnregisteredDeviceTokens = $pushAndroid->getUnregisteredDeviceTokens();

            

            if (count($androidUnregisteredDeviceTokens)>0){
                token_push_m::whereIn('token_mobile_push', $androidUnregisteredDeviceTokens)->delete();
            }



        }

        if(count($ios) > 0)
        {
            $pushIos = new PushNotification('apn');
            $pushIos->setMessage([
                'aps' => [
                    'alert' => [
                        'title'=>isset($this->data['title'])?$this->data['title']:'',
                        'body'=>isset($this->data['body'])?$this->data['body']:'',
                    ],
                    'sound' => isset($this->data['sound'])?$this->data['sound']:'',
                    'badge' => isset($this->data['badge'])?$this->data['badge']:''

                ],
                'extraPayLoad' => isset($this->data['addition_data'])?$this->data['addition_data']:[]

            ])
                ->setDevicesToken($ios->toArray())
                ->send()->getFeedback();
            $iosUnregisteredDeviceTokens = $pushIos->getUnregisteredDeviceTokens();
            if (count($iosUnregisteredDeviceTokens )>0){
                token_push_m::whereIn('token_mobile_push', $iosUnregisteredDeviceTokens)->delete();
            }

        }


        if ($this->recursion){
            $offset = $this->offset + $this->limit;
            $usersToken = User::get_users_tokens($this->device_type,$offset,$this->limit);

            if (count($usersToken) >0 ){
                $this->dispatch(new send_push(
                    $usersToken,
                    $this->data,
                    $offset,
                    $this->limit
                ));
            }

        }




    }




}
