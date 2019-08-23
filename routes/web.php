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

$router->get('/', 'CardController@getVersion');

$router->group(['middleware' => 'App\Http\Middleware\Type', 'prefix' => '{type}'], function () use ($router){

    $router->get('/', 'CardController@getCards');

    $router->post('/', 'CardController@addCard');

    $router->post('/votes/{id}', 'CardController@voteCard');

    $router->group(['middleware' => 'App\Http\Middleware\Auth'], function () use ($router){

        $router->put('/{id}', 'CardController@modCard');

        $router->delete('/{id}', 'CardController@deleteCard');
    });
});
