<?php

use App\Services\Resolver;
use App\Services\Shortener;
use App\Url;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ResolverTest extends TestCase
{
    use DatabaseTransactions;

    protected $shortener;

    protected $resolver;

    public function setUp()
    {
        parent::setUp();

        $this->shortener = new Shortener();
        $this->resolver = new Resolver();
    }

    /**
     * @test
     */
    public function fetches_url_from_database_by_token()
    {
        $url = 'https://phpunit.de/manual/4.8/en/appendixes.assertions.html';

        $token = $this->shortener->make($url, 'phpunit');
        $resolved = $this->resolver->make($token);

        $this->assertEquals($url, $resolved);
    }

    /**
     * @test
     * @expectedException Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function see_if_invalid_token_throws_exception()
    {
        $this->resolver->make('hello');
    }

    public function see_if_count_was_incremented()
    {
        $this->shortener->make('https://phpunit.de/manual/4.8/en/appendixes.assertions.html', 'phpunit');
        $this->resolver('phpunit');

        $result = Url::where('token', 'phpunit')->first();

        $this->assertEquals('1', $result['visits']);
    }
}