<?php

namespace App\Adapters\Implementation;

use App\Adapters\IRequestAdapter;
use App\Name;
use Carbon\Carbon;
use Config;
use DB;

class RequestAdapter implements IRequestAdapter
{



    public function getNames($request)
    {


       return Name::with('nameTranslates')->get();

//        $paginate = 0;
//        $cond =[];
//        if(isset($request['paginate'])){
//            $paginate = $request['paginate'];
//        }
//
//        $order_by_col  = "products.created_at";
//        if(isset($request['order_key'])){
//            $order_by_col = "products.".$request['order_key'];
//        }
//
//
//        return n::get_products(
//            $additional_and_wheres  = $cond,
//            $free_conditions        = $free_conditions,
//            $order_by_col           = $order_by_col,
//            $order_by_type          = $order_by_type,
//            $limit                  = 0 , $offset               = 0,
//            $paginate               = $paginate ,
//            $return_obj             = "no",
//            []
//        );

    }



}
