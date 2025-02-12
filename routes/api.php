<?php

use CloudCreativity\LaravelJsonApi\Facades\JsonApi;
use CloudCreativity\LaravelJsonApi\Routing\RelationshipsRegistration;
use CloudCreativity\LaravelJsonApi\Routing\RouteRegistrar;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

JsonApi::register('v1')->authorizer('default')->routes(function (RouteRegistrar $api) {

    $api->resource('users')
        ->only('read')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations->hasMany('work-bits')->readOnly();
        });;

    $api->resource('work-bits')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations->hasOne('author')->readOnly();
        });;;

});
