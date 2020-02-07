<?php
/**
 * Created by PhpStorm.
 * User: eng.ahmedbakr
 * Date: 07/08/2018
 * Time: 04:01 م
 */

namespace App\Http\Controllers\traits;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\models\attachments_m;
use Auth;


trait imagesTrait
{

    /**
     * @param $request >> received by form
     * @param int $user_id >> from current session
     * @param $file_name >> from input file name
     * @param $folder >> /folder_name under uploads
     * @param int $width
     * @param int $height
     * @param array $ext_arr >> additional array of allowed extensions
     * @param bool $return_only_name
     * @param string $absolute_upload_path
     * @return array|string >> array if uploaded
     */
    public function cms_upload(
        $request    , $user_id  = 0, $file_name     , $folder,
        $width = 0  , $height   = 0, $ext_arr = []  ,
        $return_only_name   = false, $absolute_upload_path = ""
    )
    {

        $uploaded = array();
        if (!empty($file_name) && isset($request))
        {

            if ($file_objs = $request->file($file_name))
            {

                if(!is_array($file_objs)){
                    $file_objs = [$file_objs];
                }

                foreach ($file_objs as $key => $file_obj) {

                    if ($file_obj == null){
                        continue;
                    }

                    $uploaded_file_ext              = $file_obj->getClientOriginalExtension();
                    $uploaded_origin_file_name      = $file_obj->getClientOriginalName().'.'.$uploaded_file_ext;
                    $uploaded_file_encrypted_name   = md5($user_id.time().$file_name.$file_obj->getClientOriginalName()).".".$uploaded_file_ext;
                    $uploaded_file_path             = "uploads".$folder;

                    $uploaded_full_path_to_file     = $uploaded_file_path.'/'.$uploaded_file_encrypted_name;

                    if ($absolute_upload_path != "")
                    {
                        $uploaded_file_path         = $absolute_upload_path;
                    }

                    if (
                        in_array($uploaded_file_ext,
                            [
                                "mp3","mp4","jpeg","png","jpg","MP4",
                                "JPEG","PNG","JPG","xls","XLS","doc",
                                "docx","zip","rar","xlsx","XLSX","csv",
                                "CSV","pdf","PDF","gif","GIF","svg","pem",'json'
                            ]
                        ) || (count($ext_arr) > 0 && in_array($uploaded_file_ext, $ext_arr)))
                    {
                        $file_obj->move($uploaded_file_path,$uploaded_file_encrypted_name);

                        if ($width > 0 && $height > 0)
                        {
                            $img = Image::make(($uploaded_full_path_to_file))->resize($width, $height);
                            $img->save(($uploaded_full_path_to_file),70);
                        }

                        if ($return_only_name == true || $return_only_name == "true")
                        {
                            $uploaded[] = $uploaded_file_encrypted_name;
                        }
                        else{
                            $uploaded[] = $uploaded_full_path_to_file;
                        }

                    }
                    else
                    {
                        return "not allowed type";
                    }

                }

            }
            else{
                return "There is not file to upload";
            }

        }
        else{
            return "There is not input file or coming request !!";
        }

        return $uploaded;

    }

    /**
     * @param $request >> received by form
     * @param null $item_id >> null for insert || id for edit
     * @param $img_file_name >> from input file name
     * @param $new_title
     * @param $new_alt
     * @param $upload_new_img_check
     * @param $upload_file_path >> /folder_name
     * @param $width
     * @param $height
     * @param $photo_id_for_edit
     * @param array $ext_arr
     * @return int|string
     */
    public function general_save_img(
        $request            , $item_id = null   , $img_file_name        ,
        $new_title          , $new_alt          , $upload_new_img_check ,
        $upload_file_path   , $width            , $height               ,
        $photo_id_for_edit  , $ext_arr = []

    )
    {

        $new_title  = ($new_title==null)?"":$new_title;
        $new_alt    = ($new_alt==null)?"":$new_alt;

        //$item_id could be pro id , cat_id any thing
        $photo_id   = "not_enter";

        if(!isset($this->user_id)){
            $this->user_id =1;
        }

        $upload_img = $this->cms_upload($request,$this->user_id,$img_file_name,$upload_file_path,$width,$height,$ext_arr);


        if ($item_id == null)
        {
            //save attachment first
            if(!is_array($upload_img))
            {
                return 0;
            }

            if ((!(count($upload_img)>0) && !is_array($upload_img)) || (!(count($upload_img)>0) && is_array($upload_img)) )
            {
                return 0;
            }

            //save main photo
            $upload_img = $upload_img[0];
            $photo_id   = attachments_m::save_img(null,$new_title,$new_alt,$upload_img);

            return $photo_id;
        }//end check of upload file


        if ($item_id != null && $photo_id_for_edit > 0) {

            //edit photo data
            //update image info

            if (is_array($upload_img) && $upload_new_img_check == "on")
            {
                $photo_id   = attachments_m::save_img($photo_id_for_edit,$new_title,$new_alt,$upload_img[0]);
                return $photo_id;
            }
            $photo_id       = attachments_m::save_img($photo_id_for_edit,$new_title,$new_alt);
        }

        if ($item_id != null && $photo_id_for_edit == 0) {
            //add new photo data if edit item has new image
            if (is_array($upload_img) && $upload_new_img_check == "on")
            {
                $photo_id   = attachments_m::save_img($photo_id_for_edit,$new_title,$new_alt,$upload_img[0]);
                return $photo_id;
            }
            elseif (is_array($upload_img) && count($upload_img) > 0)
            {
                $photo_id   = attachments_m::save_img($photo_id_for_edit,$new_title,$new_alt,$upload_img[0]);
                return $photo_id;
            }
            else{
                return $photo_id_for_edit;
            }

        }

        return $photo_id;
    }

    /**
     * @param $request >> from form
     * @param string $field_name >> form_input_file_name
     * @param int $width
     * @param int $height
     * @param $new_title_arr
     * @param $new_alt_arr
     * @param string $json_values_of_slider
     * @param string $path >> /folder_name
     * @param string $old_title_arr old values of existing imgages
     * @param string $old_alt_arr old values of existing images
     * @return array|string
     */
    public function general_save_slider(
        $request        , $field_name = ""  , $width = 0                , $height = 0,
        $new_title_arr  , $new_alt_arr      , $json_values_of_slider = [] ,
        $old_title_arr  , $old_alt_arr      , $path=""
    )
    {

        if ($path == "") {
            $path       = $field_name;
        }

        if(!isset($this->user_id)){
            $this->user_id =1;
        }

        //upload new files
        $slider_file    = $this->cms_upload($request , $this->user_id,"$field_name", $folder = "$path", $width, $height);//array

        //update old_photos
        if (is_array($json_values_of_slider) && count($json_values_of_slider)) {
            foreach ($json_values_of_slider as $key => $value) {
                $save_img_title     = "";
                if(isset($old_title_arr[$key])){
                    $save_img_title = $old_title_arr[$key];
                }

                $save_img_alt="";
                if(isset($old_alt_arr[$key])){
                    $save_img_alt   = $old_alt_arr[$key];
                }

                $old_photo_id       = attachments_m::save_img($value,$save_img_title,$save_img_alt);
            }
        }

        //add new photos
        if (is_array($slider_file) && count($slider_file)) {
            foreach ($slider_file as $key => $value) {
                $save_img_title     = "";
                if(isset($new_title_arr[$key])){
                    $save_img_title = $new_title_arr[$key];
                }

                $save_img_alt="";
                if(isset($new_alt_arr[$key])){
                    $save_img_alt   = $new_alt_arr[$key];
                }

                $json_values_of_slider[] = attachments_m::save_img(null,$save_img_title,$save_img_alt,$value);
            }//end foreach
        }

        return $json_values_of_slider;
    }

    public function getFormattedImage($folder,$target_image)
    {

        $get_segments   = \Illuminate\Support\Facades\Request::segments();
        $count_segments = count($get_segments);
        if($count_segments > 1)
        {
            $target_image   = $get_segments[$count_segments-1];
            $folder         = $get_segments[$count_segments-2];
        }

        $generate_path = $get_segments[0]."/";
        foreach($get_segments as $key => $item)
        {

            if($key == 0 || $key == ($count_segments - 1))
            {
                continue;
            }

            $generate_path .= "$item/";
        }

        $target_image = explode('-',$target_image);

        $return_image_url = get_format_image("$generate_path".$target_image[0],$target_image[1]);

        return Image::make($return_image_url)->response();
    }

    public function edit_slider_item(Request $request){

        $img_id     = intval(clean($request->get("img_id")));
        $att_obj    = attachments_m::find($img_id);
        $output     = [];

        if(!is_object($att_obj)){
            $att_obj = attachments_m::create([
                "id"    => $img_id,
                "path"  => "",
                "title" => "",
                "alt"   => ""
            ]);
        }

        $upload_path    = $this->cms_upload(
            $request                   ,
            $user_id                = 0,
            $file_name              = "new_file",
            $folder                 = "/new_slider_items",
            $width                  = 0,
            $height                 = 0,
            $ext_arr                = [],
            $return_only_name       = false,
            $absolute_upload_path   = ""
        );

        $output["msg"] = "Failed";

        if(is_array($upload_path) && count($upload_path)){
            $att_obj->update([
                "path"  =>  $upload_path[0]
            ]);

            $output["file_path"]    = url("/".$upload_path[0]);
            $output["msg"]          = "Done";
        }

        echo json_encode($output);
    }

}