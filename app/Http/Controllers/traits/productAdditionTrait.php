<?php
/**
 * Created by PhpStorm.
 * User: eng.ahmedbakr
 * Date: 07/08/2018
 * Time: 04:01 م
 */

namespace App\Http\Controllers\traits;
use App\models\orders\orders_m;
use App\models\products\product_addition_category_m;
use App\models\products\products_m;


trait productAdditionTrait
{

    public function getSidebarStatisticsAdditions($product_id)
    {

        $product_id = intval(clean($product_id));
        if($product_id > 0)
        {

            #region get products options
                $this->data["product_addition_count"] = product_addition_category_m::where("product_id",$product_id)->count();
            #endregion

        }

    }



}