<?php

namespace App\Services;

use App\Url;

class Resolver
{
    /**
     * Fetches the url by it's token.
     * @param $token
     * @return mixed
     */
    public function make($token)
    {
        $result = Url::where('token', $token)->firstOrFail();

        $result->incrementVisits();

        return $result['url'];
    }
}