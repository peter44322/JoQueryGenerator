<?php
Route::group(['namespace'=>'Peterzaccha\JoQueryGenerator\Controllers'],function (){
    Route::post('jo-query-generator-route/{slug}','Router@call');
});
