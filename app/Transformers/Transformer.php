<?php

namespace App\Transformers;


abstract class Transformer
{
    public $from = [];
    /**
     * Transform a collection of items to custom output
     * @param  [array] $items [collection of items]
     * @return [mixed]        [counts of the transforming method]
     */
    public function transformCollection(array $items,$function="transform")
    {
        return array_values(array_filter(array_map([$this,$function], $items)));
    }

    /**
     * retrieve certain key if exists in the $from data array
     * @param  string $key 				  [the element key in array]
     * @param  mixed  $DefaultReturnValue [default return if not found]
     * @return mixed       				  [value or Null]
     */
    public function transformThis($key, $DefaultReturnValue = Null)
    {
        if(!empty($this->from))
        {
            return (isset($this->from[$key]) ? $this->from[$key] : $DefaultReturnValue);
        }
    }
    /**
     * retrieve values from given keys in form of array in the $from data array
     * @param  array  $keys [the elements keys in array]
     * @return array        [elements values]
     */
    public function transformThisArray($keys = [] , $DefaultReturnValue = NULL)
    {
        foreach ($keys as $key)
        {
            $return[] = $this->transformThis($key , $DefaultReturnValue);
        }
        return $return;
    }



    /**
     * abstract method to custom transform an item
     * @param  [mixed] $item [item to be transformed]
     * @return [mixeed]       [counts of the implementation in child class]
     */
    public abstract function transform($item);


}

//end