<?php
/**
 * Created by PhpStorm.
 * User: eng.ahmedbakr
 * Date: 07/08/2018
 * Time: 04:01 م
 */

namespace App\Http\Controllers\traits;
use App\models\orders\orders_m;
use App\models\products\products_m;
use App\models\store\store_categories_m;
use App\models\store\store_drivers_m;
use App\models\store\store_offers_m;
use App\models\store\store_reviews_m;
use App\models\store\store_working_days_m;
use App\models\store\stores_m;


trait storeTrait
{

    public function getSidebarStatistics($store_id)
    {

        $store_id = intval(clean($store_id));
        if($store_id > 0)
        {

            #region get products

                $this->data["store_products"] = products_m::where("store_id",$store_id)->count();

            #endregion

            #region get orders

                $this->data["store_orders"] = orders_m::where("store_id",$store_id)->count();

            #endregion

            #region get categories

                $this->data["store_categories"] = store_categories_m::where("store_id",$store_id)->count();

            #endregion

            #region get working_days

                $this->data["store_working_days"] = store_working_days_m::where("store_id",$store_id)->count();

            #endregion

            #region get reviews

                $this->data["store_waiting_reviews"] = store_reviews_m::where([
                    "store_id"      => $store_id,
                    "is_reviewed"   => 0,
                ])->count();

                $this->data["store_approved_reviews"] = store_reviews_m::where([
                    "store_id"      => $store_id,
                    "is_reviewed"   => 1,
                ])->count();

                $this->data["store_reviews"] = ($this->data["store_waiting_reviews"] + $this->data["store_approved_reviews"]);

            #endregion


            #region get drivers

            $this->data["store_drivers"] = store_drivers_m::where("store_id",$store_id)->count();

            #endregion


        }

    }

    public function checkIfStoreExist($store_id)
    {

        $cond       = [];
        $cond[]     = ["stores.store_id","=",$store_id];
        $item_data  = stores_m::get_stores(
            $additional_and_wheres  = $cond, $free_conditions   = "",
            $order_by_col           = "", $order_by_type        = "",
            $limit                  = 0 , $offset               = 0,
            $paginate               = 0 , $return_obj           = "yes"
        );
        abort_if((!is_object($item_data)),404);

        $this->data["store_data"]   = $item_data;
        $this->data["store_id"]     = $store_id;

    }


}