<?php
/**
 * Created by PhpStorm.
 * User: eng.ahmedbakr
 * Date: 07/08/2018
 * Time: 04:01 م
 */

namespace App\Http\Controllers\traits;

use Illuminate\Support\Facades\Input;
use View;


trait ckeditorTrait
{

    public function ckeditor_upload(){

        \Debugbar::disable();
        if(isset($_FILES['upload'])){
            if(!file_exists("uploads/ckeditor")){
                mkdir("uploads/ckeditor");
            }

            $filen = $_FILES['upload']['tmp_name'];
            $con_images = "uploads/ckeditor/".$_FILES['upload']['name'];
            move_uploaded_file($filen, $con_images );

            $url = url($con_images);

            $funcNum = $_GET['CKEditorFuncNum'] ;
            // Optional: instance name (might be used to load a specific configuration file or anything else).
            $CKEditor = $_GET['CKEditor'] ;
            // Optional: might be used to provide localized messages.
            $langCode = $_GET['langCode'] ;

            // Usually you will only assign something here if the file could not be uploaded.
            $message = '';
            echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
        }
    }

    public function ckeditor_browse(){
        \Debugbar::disable();
        $this->data["search_for_file"]=Input::get("search_for_file");


        return view("front.subviews.browse_files",$this->data);
    }
}