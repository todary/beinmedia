<?php
/**
 * Created by PhpStorm.
 * User: eng.ahmedbakr
 * Date: 07/08/2018
 * Time: 04:01 م
 */

namespace App\Http\Controllers\traits;
use App\models\orders\orders_m;
use App\models\products\product_options_m;
use App\models\products\products_m;
use App\models\store\store_categories_m;
use App\models\store\store_offers_m;
use App\models\store\store_reviews_m;
use App\models\store\store_working_days_m;
use App\models\store\stores_m;


trait productTrait
{

    public function getSidebarStatistics($product_id)
    {

        $product_id = intval(clean($product_id));
        if($product_id > 0)
        {

            #region get products options

                $this->data["product_options_count"] = product_options_m::where("product_id",$product_id)->count();

            #endregion

            #region get product offers

                $this->data["store_offers_count"] = store_offers_m::where("product_id",$product_id)->count();

            #endregion


        }

    }

    public function checkIfProductExist($product_id)
    {

        $cond       = [];
        $cond[]     = ["products.product_id","=",$product_id];
        $item_data  = products_m::get_products(
            $additional_and_wheres  = $cond, $free_conditions   = "",
            $order_by_col           = "", $order_by_type        = "",
            $limit                  = 0 , $offset               = 0,
            $paginate               = 0 , $return_obj          = "yes"
        );
        abort_if((!is_object($item_data)),404);

        $this->data["product_data"]   = $item_data;
        $this->data["product_id"]     = $product_id;

    }


}