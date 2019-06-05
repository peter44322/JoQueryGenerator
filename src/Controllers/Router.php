<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
namespace Peterzaccha\JoQueryGenerator\Controllers;
use App\Datatables\pp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;
use Peterzaccha\JoQueryGenerator\Services\JoQueryGenerator;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use ReflectionClass;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Yajra\DataTables\DataTables;

class Router extends Controller
{

    public function call(Request $request, $slug){

        $class = 'App\DataTables\\'.$slug;

        if (class_exists($class))
        {
            $obj = new $class;
            $qg = new JoQueryGenerator($request,$obj);
            return  Datatables::of($qg->render())->make();
        }
      //  throw new NotFoundHttpException();
    }
}
