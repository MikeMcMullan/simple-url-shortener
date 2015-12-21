<?php

namespace App\Http\Controllers;

use App\Exceptions\DuplicateTokenException;
use App\Services\Resolver;
use App\Services\Shortener;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @param Request $request
     * @param Shortener $shortener
     * @return \Illuminate\Http\JsonResponse
     */
    public function shorten(Request $request, Shortener $shortener)
    {
        $data = $request->only(['url', 'custom', 'password']);

        $validator = $this->getValidationFactory()->make($request->all(), [
            'custom'    => 'min:2|max:20|alpha_num',
            'url'       => 'required|url',
            'password'  => 'in:mike2015'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $msg = '';

            if ($messages->has('url')) {
                $msg = 'A valid url is required.';
            } else if ($messages->has('custom')) {
                $msg = 'Custom token must be between 2 and 20 characters in length and contain only letters and numbers.';
            } else if ($messages->has('password')) {
                $msg = 'Incorrect password.';
            }

            return response()->json(['error' => $msg], 400);
        }

        $parsed = parse_url($data['url'], PHP_URL_HOST);

        if ($parsed === $request->getHost()) {
            return response()->json(['error' => 'Cannot shorten a url belonging to this domain.'], 400);
        }

        try {
            $token = $shortener->make($data['url'], $data['custom']);
        } catch (DuplicateTokenException $e) {
            return response()->json(['error' => 'Custom token already in use.'], 409);
        }

        return response()->json(['shorturl' => $request->root() .'/' . $token]);
    }

    /**
     * @param $token
     * @param Resolver $resolver
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resolve($token, Resolver $resolver)
    {
        return redirect($resolver->make($token), 301);
    }
}
