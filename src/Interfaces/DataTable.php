<?php


namespace Peterzaccha\JoQueryGenerator\Interfaces;


class DataTable
{
    public function joins(){
     return [];
    }
    public function selections(){
        return [];
    }
    public function defaultSelection(){
        return [];
    }
    public function query(){
        return null;
    }
//    public function slug(){
//        return __CLASS__;
//    }

    public static function url(){
        $nameArray = explode('\\',static::class);
        return url('jo-query-generator-route/'.end($nameArray));
    }
}