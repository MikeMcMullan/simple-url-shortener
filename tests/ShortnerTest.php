<?php

use App\Services\Shortener;
use App\Url;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShortenerTest extends TestCase
{
    use DatabaseTransactions;

    private $shortener;

    public function setUp()
    {
        parent::setUp();

        $this->shortener = new Shortener();
    }

    /**
     * @test
     */
    public function creates_token_for_url()
    {
        $token = $this->shortener->make('https://phpunit.de/manual/4.8/en/appendixes.assertions.html');

        $this->assertRegExp('/^[a-z0-9]{5}$/i', $token);

        $result = Url::where('token', $token)->first();

        $this->assertNotNull($result);
    }

    /**
     * @test
     */
    public function creates_custom_token_for_url()
    {
        $customToken = 'phpunit';

        $token = $this->shortener->make('https://phpunit.de/manual/4.8/en/appendixes.assertions.html', $customToken);

        $this->assertEquals($customToken, $token);
    }

    /**
     * @test
     * @expectedException App\Exceptions\DuplicateTokenException
     */
    public function throws_exception_when_duplicate_custom_token_is_created()
    {
        $customToken = 'phpunit';

        $this->shortener->make('https://phpunit.de/manual/4.8/en/appendixes.assertions.html', $customToken);
        $this->shortener->make('https://phpunit.de/manual/4.8/en/appendixes.assertions.html', $customToken);
    }

    /**
     * @test
     */
    public function creates_same_token_when_existing_url_is_provided()
    {
        $token1 = $this->shortener->make('https://phpunit.de/manual/4.8/en/appendixes.assertions.html');
        $token2 = $this->shortener->make('https://phpunit.de/manual/4.8/en/appendixes.assertions.html');

        $this->assertEquals($token1, $token2);
    }
}