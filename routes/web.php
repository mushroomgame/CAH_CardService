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

$router->get('/{type}', 'CardController@getCards');

$router->post('/{type}', 'CardController@addCard');

$router->put('/{type}/{id}', 'CardController@modCard');

$router->post('/{type}/votes/{id}', 'CardController@voteCard');