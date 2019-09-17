<?php
Route::group([
    'namespace'=>'Peterzaccha\JoQueryGenerator\Controllers',
    'middleware'=>config('jo-query-generator.middleware')
],function (){
    Route::post('jo-query-generator-route/{slug}/{params?}','Router@call');

});
