<?php


namespace Peterzaccha\JoQueryGenerator\Interfaces;


use Illuminate\Support\Facades\Schema;
use Peterzaccha\JoQueryGenerator\Services\JoQueryGenerator;
use Yajra\DataTables\DataTables;

class DataTable
{
    public  function joins(){
     return [];
    }
    public  function selections(){
        return [];
    }
    public  function defaultSelection(){
        return [];
    }

    public  function titles(){
        return [];
    }
    public  function query(){
        return null;
    }

    public  function conditions($query){
        return $query;
    }

    public static function url(){
        $nameArray = explode('\\',static::class);
        return url('jo-query-generator-route/'.end($nameArray));
    }

    protected  function tableTitle($classes =""){
        $html = '';
        $titles = $this->titles();
        foreach ($this->defaultSelection() as $selection){
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

        foreach ($this->selections() as $key=>$value){
            foreach ($value as $selection){
                $name = explode(' as ',$selection)[0];
                $data = explode(' as ',$selection)[1];
                $name = trim($name);
                $data = trim($data);
                $title = isset($titles[$data]) ? $titles[$data] : $data;
                $html .= "<th data-data='${data}' data-name='${name}' data-visible='0' class='${classes}'>${title}</th>";
            }
        }
        foreach ($this->customColumns() as $key=>$value){
            $title = isset($titles[$key]) ? $titles[$key] : $key;
            $html .= "<th data-data='${key}' data-name='${key}' data-visible='0' class='${classes}'>${title}</th>";
        }

        return $html;
    }

    public function render(JoQueryGenerator $queryGenerator){
        $dataTable = Datatables::of($queryGenerator->render());
        foreach (static::customColumns() as $name=>$function){
            $dataTable->addColumn($name,$function);
        }
        return $dataTable->rawColumns($this->rawColumns());
    }

    public  function customColumns(){
        return [];
    }

    public function rawColumns(){
        return [];
    }
}