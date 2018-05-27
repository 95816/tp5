<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
/*
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];*/


use think\Route;

Route::rule('api/:version/banner/:id', 'api/:version.Banner/getBannerId', 'post|get');


Route::group('api/:version/theme', function () {
    Route::any('', 'api/:version.Theme/getSimpleList');
    Route::any(':id', 'api/:version.Theme/getComplexOne', ['method'=>'get|post'], ['id' => '\d+']);
}, ['method' => 'get|post']);
//Route::rule('api/:version/theme', 'api/:version.Theme/getSimpleList', 'post|get');
//Route::rule('api/:version/theme/:id', 'api/:version.Theme/getComplexOne', 'post|get');

//商品路由
Route::group('api/:version/product', [
    ':id' => ['api/:version.Product/getOne', [], ['id' => '\d+']],
    'recent' => ['api/:version.Product/getRecent'],
    'by_category' => ['api/:version.Product/getAllInCategory']
], ['method' => 'get|post']);


Route::rule('api/:version/category/all', 'api/:version.Category/getAllCategories', 'post|get');

//WeChat API
Route::rule('api/:version/token/user', 'api/:version.Token/getToken', 'post');
