<?php

namespace App\Utils;

class JsonUtils
{
    /**
     * Filter out empty and null values from an array or object.
     *
     * @param mixed $data The data to filter (array or object).
     * @return array Filtered array.
     */
    public static function filterEmptyAndNull(object $data): object
    {
        foreach($data as $property => $value){
            if($value === null || $value === '' || $value === [])
            {
                unset($data->$property);
            }
        }
        //for convienence
        return $data;
    }
}
