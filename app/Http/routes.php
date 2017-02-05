<?php

$app->get('/', function () {
    return view('index');
});

$app->group(['middleware' => 'throttle:3,1'], function () use ($app) {
    $app->get('/ytshare/{url:.*}', function (Illuminate\Http\Request $request) {
        $videoID = $request->get('ci');

        if (! $videoID || !preg_match('/^[\w-]{1,15}$/i', $videoID)) {
            return response('No video id found.');
        }

        $html = file_get_contents("https://www.youtube.com/shared?ci={$videoID}");

        preg_match('/<link rel="shortlink" href="https:\/\/youtu.be\/([\w-]+)"/', $html, $matches);

        if (! empty($matches)) {
            return redirect('https://youtube.com/watch?v=' . $matches[1]);
        } else {
            return response('Unable to find real youtube url.');
        }
    });
});

$app->get('/shorten', ['uses' => 'HomeController@shorten']);
$app->get('/{token:[a-zA-Z0-9]{2,20}}', ['uses' => 'HomeController@resolve']);
