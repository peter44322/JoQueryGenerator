<?php
Route::group(['namespace'=>'Peterzaccha\JoQueryGenerator\Controllers'],function (){
    Route::get('jo-query-generator-route/{slug}','Router@call');
});
