<?php

namespace App\Services;

use App\Exceptions\DuplicateTokenException;
use App\Url;
use Illuminate\Database\Eloquent\Model;

class Shortener
{
    /**
     * Check whether a value exists in a column.
     * @param $url
     *
     * @return null|Model
     */
    protected function exists($column, $value)
    {
        return Url::where($column, $value)->first();
    }

    /**
     * Generate a unique 5 character token.
     *
     * @return string
     */
    protected function token()
    {
        $token = false;

        while ( ! $token) {
            $generated = str_random(5);

            if ( ! $this->exists('token', $generated)) {
                $token = $generated;
            }
        }

        return $token;
    }

    /**
     * Create token for a url.
     *
     * @param $longUrl
     * @param null $customToken
     * @return string
     */
    public function make($longUrl, $customToken = null)
    {
        if ($customToken) {
            if ($this->exists('token', $customToken)) {
                throw new DuplicateTokenException;
            }

            $token = $customToken;
        } else {
            $duplicate = $this->exists('url', $longUrl);

            if ($duplicate) {
                return $duplicate['token'];
            }

            $token = $this->token();
        }

        Url::create([
            'token' => $token,
            'url' => $longUrl
        ]);

        return $token;
    }
}