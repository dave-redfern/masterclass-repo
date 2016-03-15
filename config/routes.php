<?php

$namespace = 'App\\Controllers\\';

return [

    ['path' => '/',               'type' => 'GET',  'handler' => $namespace . 'IndexController@index'],
    ['path' => '/story/',         'type' => 'GET',  'handler' => $namespace . 'StoryController@index'],
    ['path' => '/story/create',   'type' => 'GET',  'handler' => $namespace . 'StoryController@create', 'auth' => true],
    ['path' => '/story/create',   'type' => 'POST', 'handler' => $namespace . 'StoryController@create', 'auth' => true],
    ['path' => '/comment/create', 'type' => 'POST', 'handler' => $namespace . 'CommentController@create', 'auth' => true],
    ['path' => '/user/create',    'type' => 'GET',  'handler' => $namespace . 'UserController@create'],
    ['path' => '/user/create',    'type' => 'POST', 'handler' => $namespace . 'UserController@create'],
    ['path' => '/user/account',   'type' => 'GET',  'handler' => $namespace . 'UserController@account', 'auth' => true],
    ['path' => '/user/account',   'type' => 'POST', 'handler' => $namespace . 'UserController@account', 'auth' => true],
    ['path' => '/user/login',     'type' => 'GET',  'handler' => $namespace . 'UserController@login'],
    ['path' => '/user/login',     'type' => 'POST', 'handler' => $namespace . 'UserController@login'],
    ['path' => '/user/logout',    'type' => 'GET',  'handler' => $namespace . 'UserController@logout'],

];
