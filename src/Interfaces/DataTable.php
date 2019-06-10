<?php


namespace Peterzaccha\JoQueryGenerator\Interfaces;


use Illuminate\Support\Facades\Schema;
use Peterzaccha\JoQueryGenerator\Services\JoQueryGenerator;
use Yajra\DataTables\DataTables;

class DataTable
{
    public static function joins(){
     return [];
    }
    public static function selections(){
        return [];
    }
    public static function defaultSelection(){
        return [];
    }

    public static function titles(){
        return [];
    }
    public static function query(){
        return null;
    }
//    public function slug(){
//        return __CLASS__;
//    }

    public static function url(){
        $nameArray = explode('\\',static::class);
        return url('jo-query-generator-route/'.end($nameArray));
    }

    public static function tableTitle($classes =""){
        $html = '';
        $titles = static::titles();
        foreach (static::selections() as $key=>$value){
            foreach ($value as $selection){
                $name = explode(' as ',$selection)[0];
                $data = explode(' as ',$selection)[1];
                $name = trim($name);
                $data = trim($data);
                $title = isset($titles[$data]) ? $titles[$data] : $data;
                $html .= "<th data-data='${data}' data-name='${name}' data-visible='0' class='${classes}'>${title}</th>";
            }
        }
        foreach (static::defaultSelection() as $selection){
            $table=explode('.',$selection)[0];
            $star=explode('.',$selection)[1];
            if (trim($star) == '*'){
                foreach (Schema::getColumnListing($table) as $column){
                    $name = $table.'.'.$column;
                    $title = isset($titles[$column]) ? $titles[$column] : $column;
                    $html .= "<th data-data='${column}' data-name='${name}' data-visible='0' class='${classes}'>${title}</th>";
                }
            }

        }
        return $html;
    }

    public function render(JoQueryGenerator $queryGenerator){
        Datatables::of($queryGenerator->render());
    }
}