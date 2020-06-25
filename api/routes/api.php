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
], function () use ($router) {
        $router->get('/{noradId:[0-9]+}', 'SatelliteController@getSatellite');
        $router->get('/{noradId:[0-9]+}/predictions/{lat}/{lng}[/{alt:[0-9]+}]', 'SatelliteController@getPredictions');
    });

$router->group([
    'prefix' => 'subscribe',
    'namespace' => '\App\Http\Controllers'
], function () use ($router) {
        $router->post('/', 'SubscriberController@subscribe');
        $router->put('/', 'SubscriberController@remove');
    });

$router->get('/', function () use ($router) {
    return $router->app->version();
});
