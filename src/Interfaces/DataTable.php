<?php


namespace Peterzaccha\JoQueryGenerator\Interfaces;


interface DataTable
{
    public function joins();
    public function selections();
    public function defaultSelection();
}