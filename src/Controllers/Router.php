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

class Router extends Controller
{
    /**
     * @param Request $request
     * @param $slug
     * @return mixed
     */
    public function call(Request $request, $slug){

        foreach (glob(app_path().'/Datatables/*.php') as $file)
        {

            require_once $file;

            // get the file name of the current file without the extension
            // which is essentially the class name
            $class = 'App\Datatables\\'.basename($file, '.php');



            if (class_exists($class))
            {
                $obj = new $class;

                if($obj->slug() === $slug){
                    $qg = new JoQueryGenerator($request,$obj);
                    return  Datatables::of($qg->render())->make();
                }
            }
        }

        throw new NotFoundHttpException();
    }
}
