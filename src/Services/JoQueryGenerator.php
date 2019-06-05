<?php

namespace Peterzaccha\JoQueryGenerator\Services;
use Peterzaccha\JoQueryGenerator\Interfaces\DataTable;

class JoQueryGenerator
{
    private $joins = [];
    private $selectors = [];
    private $tables = [];
    private $defaultSelectors = [];
    private $tableDotColumn;

    public function __construct($tableDotColumn,DataTable $dataTable)
    {
        $this->tableDotColumn = $tableDotColumn;
        $this->defaultSelectors = $dataTable->defaultSelection();
        $this->joins = $dataTable->joins();
        $this->selectors = $dataTable->selections();

        $this->setTables();
    }

    public function setJoins(array $joins)
    {
        $this->joins = $joins;
    }

    public function setSelectors(array $selectors)
    {
        $this->selectors = $selectors;
    }

    public function setTables()
    {
        //array will be like [ 'tableName'=>['col1','col2'] ]
        foreach ($this->tableDotColumn as $item) {
            $this->tables[explode(".", $item)[0]] = [];
            array_push(
                $this->tables[explode(".", $item)[0]],
                @explode(".", $item)[1]
            );
        }
    }

    public function getCols($tableName)
    {
        if (isset($this->tables[$tableName])) {
            return $this->tables[$tableName];
        }
        return [];
    }

    private function cleanArray($array)
    {
        $a = function ($obj) {
            static $idlist = array();
            if (in_array($obj->table, $idlist))
                return false;

            $idlist[] = $obj->table;
            return true;
        };

        return array_filter($array, $a);
    }

    private function getJoin($col_name)
    {
        if (@$this->joins[explode('.', $col_name)[0]]) {
            return $this->joins[explode('.', $col_name)[0]];
        }
    }

    private function getSelector($col_name)
    {
        if (@$this->joins[explode('.', $col_name)[0]]) {
            return $this->selectors[explode('.', $col_name)[0]];
        }
    }

    public function getJoins()
    {
        $array = $this->tableDotColumn;
        $allJoins = [];
        foreach ($array as $column) {
            if ($this->getSelector($column)) {
                foreach ($this->getJoin($column) as $join)
                    array_push($allJoins, $join);
            }
        }
        return $this->cleanArray($allJoins);
    }

    public function getSelectors()
    {
        $array = $this->tableDotColumn;
        $allSelectors = $this->defaultSelectors;
        foreach ($array as $column) {
            if ($this->getSelector($column)) {
                foreach ($this->getSelector($column) as $join)
                    array_push($allSelectors, $join);
            }
        }
        return array_unique($allSelectors);
    }

    public function render($query)
    {
        foreach ($this->getJoins() as $object) {
            $query->leftJoin($object->table, function ($q) use ($object) {
                for ($i = 0; $i < count($object->first_col); $i++) {
                    $fun = 'on';//$i === 0 ? 'on' : 'orOn';
                    $q->$fun($object->first_col[$i], $object->operator, $object->second_col[$i]);
                }
            });
        }
        return $query->select($this->getSelectors());
    }

}