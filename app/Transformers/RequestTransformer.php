<?php

/**
 * Created by PhpStorm.
 * User: todary
 * Date: 23/11/17
 * Time: 10:05 م
 */

namespace App\Transformers;

use Illuminate\Support\Facades\Lang;

class RequestTransformer extends Transformer
{


    /**
     * method to custom transform an item
     * @param  [mixed] $item [item to be transformed]
     * @return [mixeed]       [counts of the implementation in child class]
     */
    public function transform($items)
    {


        $data = [];
        $allData = [];

        foreach ($items as $item){
            $data['id'] = $item->name_id;
            $data['hits'] = $item->hits;
            $data['translate'] = $this->transformTranslate($item->nameTranslates);
            $allData[] = $data;

        }


        return array_values($allData);

    }

    /**
     * @param $items
     * @return array
     */

    public function transformTranslate($items)
    {
        $data = [];
        $allData = [];

        foreach ($items as $item){
            $data['name_translate_id'] = $item->name_translate_id;
            $data['name_translate'] = $item->name_translate;
            $allData[] = $data;

        }


        return array_values($allData);
    }



}
