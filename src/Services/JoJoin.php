<?php

namespace Peterzaccha\JoQueryGenerator\Services;

class JoJoin
{
    public $table;
    public $first_col;
    public $operator;
    public $second_col;
    public $important;
    public $join;

    /**
     * leftJoin constructor.
     *
     * @param $table
     * @param $first_col
     * @param $operator
     * @param $second_col
     */
    public function __construct($table, $first_col, $operator, $second_col, $important = 0, $join = null)
    {
        $this->table = $table;
        $this->first_col = $this->toArrayIfNot($first_col);
        $this->operator = $operator;
        $this->second_col = $this->toArrayIfNot($second_col);
        $this->join = $join;
    }

    public static function toArrayIfNot($var)
    {
        return is_array($var) ? $var : [$var];
    }
}
