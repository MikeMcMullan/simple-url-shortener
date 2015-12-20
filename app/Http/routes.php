<?php

$app->get('/', function () {
    return view('index');
});

$app->get('/shorten', ['uses' => 'HomeController@shorten']);
$app->get('/{token:[a-zA-Z0-9]{2,20}}', ['uses' => 'HomeController@resolve']);
