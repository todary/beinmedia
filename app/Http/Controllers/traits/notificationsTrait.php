<?php
/**
 * Created by PhpStorm.
 * User: eng.ahmedbakr
 * Date: 07/08/2018
 * Time: 04:01 م
 */

namespace App\Http\Controllers\traits;

use App\User;
use App\models\notification_m;

trait notificationsTrait
{

    public function send_user_notification($not_title = "" , $not_type = "info" , $user_id = "")
    {
        if (!empty($not_title) && !empty($user_id))
        {
            notification_m::create([
                "not_title" => $not_title,
                "not_type" => $not_type,
                "not_to_userid" => $user_id
            ]);
        }
    }

    public function send_all_user_type_notifications($not_title = "" , $not_type = "info" , $user_type = "")
    {
        if (!empty($not_title) && !empty($user_type))
        {

            if ($user_type == "admin")
            {
                $free_conditions = " users.user_type = 'admin' OR users.user_type = 'dev' ";
                $all_users = User::get_users(
                    $additional_and_wheres = [], $free_conditions,
                    $order_by_col = "",$order_by_type = "" ,
                    $limit = 0 , $offset = 0 , $paginate = 0,
                    $default_lang_id = 1 ,$return_obj="no"
                );
            }
            else{
                $all_users = User::where("user_type",$user_type)->get();
            }

            $all_users = $all_users->all();
            if (count($all_users))
            {
                $insert_arr = [];
                $current_date = date("Y-m-d H:i:s");

                foreach($all_users as $key => $user_obj)
                {

                    $insert_arr[] = [
                        "not_title" => $not_title,
                        "not_type" => "$not_type",
                        "not_to_userid" => $user_obj->user_id,
                        "created_at" => "$current_date",
                        "updated_at" => "$current_date",
                    ];

                }

                if (count($insert_arr))
                {
                    notification_m::insert($insert_arr);
                }
            }


        }
    }

}