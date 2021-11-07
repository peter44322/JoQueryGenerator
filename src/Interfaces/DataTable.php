<?php

namespace Peterzaccha\JoQueryGenerator\Interfaces;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Peterzaccha\JoQueryGenerator\Services\JoQueryGenerator;
use Yajra\DataTables\DataTables;

class DataTable
{
    public static function joins()
    {
        return [];
    }

    public static function selections()
    {
        return [];
    }

    public static function defaultSelection()
    {
        return [];
    }

    public static function titles()
    {
        return [];
    }
    public static function query($request){
        return null;
    }

    public static function conditions($query){
        return $query;
    }

    public static function parameters(){
        return collect([]);
    }

    public static function setParameters(array $params){
        foreach ($params as $param=>$val){
            static::$$param = $val;
        }
    }

    public static function url(){
        $nameArray = explode('\\',static::class);
        return url('jo-query-generator-route/'.end($nameArray).'/'.static::parameters()->toJson());
    }

    public static function tableTitle($classes = '')
    {
        $html = '';
        $titles = static::titles();
        foreach (static::defaultSelection() as $selection){
            $table=explode('.',$selection)[0];
            $star=explode('.',$selection)[1];
            $title = isset($titles[$selection]) ? $titles[$selection] : $selection;
            if (trim($star) == '*'){
                foreach (Schema::getColumnListing($table) as $column){

                    $name = $table.'.'.$column;
                    $title = isset($titles[$column]) ? $titles[$column] : $column;
                    $html .= "<th data-data='${column}' data-name='${name}' data-visible='1' class='${classes}'>${title}</th>";
                }
            }else{
                $html .= "<th data-data='${star}' data-name='${selection}' data-visible='1' class='${classes}'>${title}</th>";
            }
        }

        foreach (static::selections() as $key=>$value) {
            foreach ($value as $selection) {
                if ($selection instanceof Expression) {
                    $selection = preg_replace('/^[A-Z_]+\([A-Z]* ?([a-z_\.]+)\)/i', '${1}', $selection->getValue());
                }
                $name = explode(' as ', $selection)[0];
                $data = explode(' as ', $selection)[1];
                $name = trim($name);
                $data = trim($data);
                $title = isset($titles[$data]) ? $titles[$data] : $data;
                $html .= "<th data-data='${data}' data-name='${name}' data-visible='0' class='${classes}'>${title}</th>";
            }
        }
        foreach (static::customColumns() as $key=>$value) {
            $title = isset($titles[$key]) ? $titles[$key] : $key;
            $html .= "<th data-data='${key}' data-name='${key}' data-visible='0' class='${classes}'>${title}</th>";
        }

        return $html;
    }

    public function render(JoQueryGenerator $queryGenerator)
    {
        $dataTable = Datatables::of($queryGenerator->render());
        foreach (static::customColumns() as $name=>$function) {
            $dataTable->addColumn($name, $function);
        }

        return $dataTable->rawColumns($this->rawColumns());
    }

    public static function customColumns()
    {
        return [];
    }

    public function rawColumns()
    {
        return [];
    }
}
