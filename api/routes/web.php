<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->group([
    'prefix' => 'satellite',
    'namespace' => '\App\Http\Controllers'
],
    function () use ($router) {

        $router->get('/{noradId:[0-9]+}', 'SatelliteController@findNoradId');

        $router->get('/{noradId:[0-9]+}/predictions', 'SatelliteController@getPredictions');

        $router->post('/subscribe', 'SubscriberController@submit');
        $router->put('/subscribe', 'SubscriberController@remove');
    });



$router->get('/', function () use ($router) {
    return $router->app->version();
});
