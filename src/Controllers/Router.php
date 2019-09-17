<?php

namespace App\Http\Controllers;

namespace Peterzaccha\JoQueryGenerator\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Peterzaccha\JoQueryGenerator\Services\JoQueryGenerator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Router extends Controller
{

    public function call(Request $request, $slug,$params=null){

        $class = 'App\DataTables\\'.$slug;

        if (class_exists($class))
        {
            $obj = new $class;
            if ($params){
                $array = json_decode($params,true);
                $obj::setParameters($array);
            }

            $qg = new JoQueryGenerator($request,$obj);

            return $obj->render($qg)->make();
        }

        throw new NotFoundHttpException();
    }
}
