<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomeControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_shortens_a_url()
    {
        $response = $this->createShortUrl([
            'custom' => null
        ]);

        $this->assertRegExp('/^http:\/\/localhost\/([a-z0-9]{5})$/i', $response->getData()->shorturl);
    }

    /**
     * @test
     */
    public function it_throws_an_error_when_shortening_a_url_that_is_apart_of_the_url_shortener_domain()
    {
        $response = $this->createShortUrl([
            'url' => 'http://localhost/phpunit'
        ]);

        $this->assertEquals('Cannot shorten a url belonging to this domain.', $response->getData()->error);
    }

    /**
     * @test
     */
    public function it_throws_an_error_when_given_wrong_password()
    {
        $response = $this->createShortUrl(['password' => 'fsdfds']);

        $this->assertEquals('Incorrect password.', $response->getData()->error);
    }

    /**
     * @test
     */
    public function it_throws_an_error_when_given_custom_token_with_the_incorrect_length()
    {
        $response = $this->createShortUrl(['custom' => 'a']);

        $this->assertEquals('Custom token must be between 2 and 20 characters in length and contain only letters and numbers.', $response->getData()->error);
    }

    /**
     * @test
     */
    public function it_throws_error_when_invalid_url_is_provided()
    {
        $response = $this->createShortUrl(['url' => 'sdfsfsdf']);

        $this->assertEquals('A valid url is required.', $response->getData()->error);
    }

    /**
     * @test
     */
    public function it_redirects_the_user_to_the_expanded_url()
    {
        $this->createShortUrl();

        $response = $this->call('GET', '/phpunit');
        $this->assertEquals('https://phpunit.de/manual/4.8/en/appendixes.assertions.html', $response->getTargetUrl());
    }

    protected function createShortUrl($params = [])
    {
        $defaults = [
            'url' => 'https://phpunit.de/manual/4.8/en/appendixes.assertions.html',
            'password' => 'mike2015',
            'custom' => 'phpunit'
        ];

        $params = array_merge($defaults, $params);

        return $this->call('GET', '/shorten?' . http_build_query($params));
    }
}