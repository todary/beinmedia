<?php
/**
 * Created by PhpStorm.
 * User: eng.ahmedbakr
 * Date: 07/08/2018
 * Time: 04:01 م
 */

namespace App\Http\Controllers\traits;

use Cache;
use App\models\site_content_m;
use App\models\generate_site_content_methods_m;
use App\models\attachments_m;

trait siteContentTrait
{



    /**
     * @param arr_of_str $content_row_title array of content_titles
     * important note the row you can fetch coreectly is the row the saved
     * by general_save_content
     *
     * $slider_imgs_field_arr== $slider_imgs_arr["edit_index_page"]=array("slider1","slider2","slider3")
     *
     */
    public function general_get_content($content_row_title=array(),$slider_imgs_field_arr=array()) {

        foreach ($content_row_title as $key => $title) {

            $cache_data=Cache::get($title."_".$this->lang_id);
            if($cache_data!=null){
                $this->data["$title"]=json_decode($cache_data);
                continue;
            }

            $this->data["$title"]="";
            $edit_content_row=site_content_m::where([
                "content_title"=>"$title",
                "lang_id"=>"$this->lang_id"
            ])->first();
            if(!is_object($edit_content_row)){
                continue;
            }
            $edit_content_row=  json_decode($edit_content_row->content_json);

            $generate_site_content_method=generate_site_content_methods_m::where("method_name","=","$title")->first();

            if(!is_object($generate_site_content_method)){
                return;
            }

            $generate_site_content_method=json_decode($generate_site_content_method->method_requirments);

            //get imgs data
            //check if there is imgs in $edit_content_row
            if (isset($edit_content_row->img_ids)&&  is_object($edit_content_row->img_ids)) {
                foreach ($edit_content_row->img_ids as $img_key => $img_id) {
                    $img_var_name=$img_key;
                    $edit_content_row->$img_var_name=attachments_m::find($img_id);
                    if(!is_object($edit_content_row->$img_var_name)){
                        $edit_content_row->$img_var_name=new \stdClass();
                        $edit_content_row->$img_var_name->path="";
                        $edit_content_row->$img_var_name->title="";
                        $edit_content_row->$img_var_name->alt="";
                    }
                }
            }

            //get slider data

            if (isset($slider_imgs_field_arr["$title"])&&  is_array($slider_imgs_field_arr["$title"])) {
                foreach ($slider_imgs_field_arr["$title"] as $key => $slider) {

                    if(!isset($edit_content_row->$slider)){
                        continue;
                    }

                    $slider_imgs_ids=$edit_content_row->$slider->img_ids;
                    $edit_content_row->$slider->imgs = array();

                    if (is_array($slider_imgs_ids) && count($slider_imgs_ids)) {
                        $edit_content_row->$slider->imgs = attachments_m::get_imgs_from_arr($slider_imgs_ids);
                    }

                }
            }

            //get selected data
            if(isset($generate_site_content_method->select_fields->fields)&&is_array($generate_site_content_method->select_fields->fields)){
                $select_fields=$generate_site_content_method->select_fields->fields;
                $select_tables=$generate_site_content_method->select_fields->tables;

                foreach ($select_fields as $key => $field) {
                    if(isset($edit_content_row->$field)){
                        //get field_value,model
                        $field_value=$edit_content_row->$field;
                        $model_name=$select_tables->$field->model;

                        $edit_content_row->$field=$model_name::find($field_value);
                    }
                }


            }
            //END get selected data

            $this->data["$title"]=$edit_content_row;

            Cache::put($title."_".$this->lang_id,json_encode($edit_content_row),60*60*30);

        }//end foreach

    }

}