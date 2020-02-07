<?php
/**
 * Created by PhpStorm.
 * User: eng.ahmedbakr
 * Date: 07/08/2018
 * Time: 04:01 م
 */

namespace App\Http\Controllers\traits;

use App\models\attachments_m;
use App\models\settings_m;

trait middlewareTrait
{

    public function setupConfig()
    {

        #region get settings data

        $settings       = settings_m::get()->groupBy("setting_key")->all();

        $settings_arr   = [];
        foreach($settings as $key => $item)
        {
            $value      = $item[0]->setting_value;
            if(in_array($item[0]->setting_type,["image", "file"]))
            {

                if($item[0]->setting_value > 0)
                {
                    $get_path = attachments_m::find($item[0]->setting_value);
                    if(is_object($get_path))
                    {
                        $value = $get_path->path;
                    }
                }

            }

            $settings_arr["$key"] = $value;

        }

        config()->set('settings_arr', $settings_arr);

        #endregion

        #region set timezone config

        date_default_timezone_set($settings_arr['timezone']);

        #endregion

        #region set mail config

        config([
            "mail.driver"           => $settings_arr['mail_type'],
            "mail.host"             => $settings_arr['smtp_host'],
            "mail.port"             => $settings_arr['smtp_port'],
            "mail.from.address"     => $settings_arr['email'],
            "mail.from.name"        => $settings_arr['name'],
            "mail.encryption"       => $settings_arr['smtp_ssl'],
            "mail.username"         => $settings_arr['smtp_user'],
            "mail.password"         => $settings_arr['smtp_pass'],
        ]);

        #endregion

    }

}