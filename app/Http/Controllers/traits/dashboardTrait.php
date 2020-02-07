<?php
/**
 * Created by PhpStorm.
 * User: eng.ahmedbakr
 * Date: 07/08/2018
 * Time: 04:01 م
 */

namespace App\Http\Controllers\traits;
use Illuminate\Http\Request;


trait dashboardTrait
{

    public function general_remove_item(Request $request, $model_name="")
    {

        $output     = [];
        $item_id    = intval(clean($request->get("item_id", 0)));

        if($model_name == ""){
            $model_name = clean($request->get("table_name")); // App\User
        }

        if ($item_id > 0) {

            $model_name::destroy($item_id);

            $output         = array();
            $removed_item   = $model_name::find($item_id);
            if (!isset($removed_item)) {
                $output["deleted"] = "yes";
            }

        }
        else{
            $output["deleted"]  = "<div class='alert alert-danger'>حدث خطأ لم يتم تنفيذ !</div>";
        }

        echo json_encode($output);

    }

    public function reorder_items(Request $request) {

        $items          = clean($request->get("items"));
        $model_name     = clean($request->get("table_name"));  // App\User
        $field_name     = clean($request->get("field_name"));

        $output         = [];

        if (is_array($items) &&  (count($items)>0)) {
            foreach ($items as $key => $value) {
                $item_id        = $value[0];
                $item_order     = $value[1];

                $returned_check = $model_name::find($item_id)->update([
                    "$field_name"   =>  $item_order
                ]);

                if ($returned_check != true) {

                    $output["error"] = "error";
                    echo json_encode($output);
                    return;
                }

            }
            $output["success"]  = "success";
        }
        else{
            $output["error"]    = "حدث خطأ لم يتم تنفيذ !";
        }

        echo json_encode($output);
    }

    public function new_accept_item(Request $request,$model_name="",$field_name="") {

        $output     = [];
        $item_id    = intval(clean($request->get("item_id",0)));

        if($model_name == ""){
            $model_name = clean($request->get("table_name"));
        }

        if($field_name == ""){
            $field_name = clean($request->get("field_name"));
        }

        $accept                 = clean($request->get("accept"));
        $item_primary_col       = clean($request->get("item_primary_col"));
        $accepters_data         = $request->get("acceptersdata");
        $accept_url             = clean($request->get("accept_url"));
        $display_block          = clean($request->get("display_block"));


        if ($item_id > 0) {
            $obj            = $model_name::find($item_id);
            $return_statues = $obj->update(["$field_name"=>"$accept"]);

            $output["msg"]  = generate_multi_accepters($accept_url,$obj,$item_primary_col,$field_name,$model_name,json_decode($accepters_data),$display_block);
        }
        else{
            $output["msg"]  = "<div class='alert alert-danger'>حدث خطأ لم يتم تنفيذ !</div>";
        }

        echo json_encode($output);
    }

    public function general_self_edit(Request $request) {

        $output                 = [];
        $item_id                = clean($request->get("item_id"));
        $model_name             = clean($request->get("table_name"));
        $field_name             = clean($request->get("field_name"));
        $value                  = clean($request->get("value"));
        $input_type             = clean($request->get("input_type"));
        $row_primary_col        = clean($request->get("row_primary_col"));

        $output["success"]      = "";
        $output["status"]       = "";

        if ($item_id > 0) {

            $item_obj       = $model_name::find($item_id);
            $return_statues = $item_obj->update(["$field_name"=>$value]);
            if($return_statues){
                $output["success"] = "success";
            }

            $output["msg"] = generate_self_edit_input(
                $url                = "",
                $item_obj,
                $item_primary_col   = $row_primary_col,
                $item_edit_col      = $field_name,
                $table              = $model_name,
                $input_type         = $input_type
            );

        }
        else{
            $output["success"] = "error";
        }

        echo json_encode($output);
    }

    public function setMetaTitle($title = "")
    {
        $this->data["meta_title"]       .= " | ".$title;
    }


}