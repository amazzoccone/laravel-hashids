<?php

namespace Bondacom\Tests;

use Bondacom\LaravelHashids\RequestDecoder;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Carbon\Carbon;

class RequestDecoderTest extends TestCase
{
    /**
     * @var Bondacom\LaravelHashids\RequestDecoder
     */
    public $decoder;

    public function setUp()
    {
        parent::setUp();

        $this->decoder = app(RequestDecoder::class);
    }

    /**
     * @param $uri
     * @param $method
     * @param array $parameters
     * @param array $headers
     */
    protected function simulateRequest($uri, $method, array $parameters = [], array $headers = [])
    {
        $request = Request::create('', $method);
        $request->headers->replace($headers);
        $request->setRouteResolver(function () use ($request, $uri, $method, $parameters) {
            return (new Route($method, $uri, []))->bind($request);
        });
    }

    /**
     * @test
     */
    public function it_decode_headers_from_requests()
    {
        $consumerId = 341;

        $request = $this->simulateRequest('users', 'GET', [], [
            'Consumer-ID' => $this->encode($consumerId)
        ]);

        $requestDecoded = $this->decoder->handle($request);

        $this->assertNotEquals($request->headers()->all(), $requestDecoded->headers()->all());
        $this->assertEquals($consumerId, $requestDecoded->headers()->get('Consumer-ID'));
    }

    /**
     * @test
     */
    public function it_decode_route_parameters_from_requests()
    {
        $userId = 341;
        $request = $this->simulateRequest('users/{{user}}', 'GET', [
            'user' => $this->encode($userId)
        ]);

        $requestDecoded = $this->decoder->handle($request);

        $this->assertNotEquals($request->route()->parameters(), $requestDecoded->route()->parameters());
        $this->assertEquals($userId, $requestDecoded->route()->parameter('user'));
    }
}