<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('token', function()
{
    if (!isset($_GET['token']) || !Token::getWithToken($_GET['token']))
    {
        return Response::json(
            array('success' => false,
                'payload' => array(),
                'error' => 'Non autorisé !'
            ),
            401);
    }
});

Route::filter('key', function()
{
    if (!isset($_GET['token']) || $_GET['token'] != Config::get('app.app_key'))
    {
        return Response::json(
            array('success' => false,
                'payload' => array(),
                'error' => 'Non autorisé !'
            ),
            401);
    }
});

Route::filter('admin', function()
{
    $token = Token::getWithToken($_GET['token']);
    $user = User::with('role')->find($token->user_id);
    if($user->role->access_level != 2)
    {
        return response()->json(
            array('success' => false,
                'payload' => array(),
                'error'   => 'Non autorisé.'
            ),
            403);
    }
});