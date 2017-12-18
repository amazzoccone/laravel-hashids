<?php

namespace Bondacom\Tests;

use Bondacom\LaravelHashids\RequestDecoder;
use Illuminate\Http\Request;
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
     * @test
     */
    public function it_decode_headers_from_requests()
    {
        $consumerId = 341;
        $request = Request::create('users', 'GET');
        $request->headers->add([
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
        $request = Request::create('users/{{user}}', 'GET', ['user' => $this->encode($userId)]);

        $requestDecoded = $this->decoder->handle($request);

        $this->assertNotEquals($request->route()->parameters(), $requestDecoded->route()->parameters());
        $this->assertEquals($userId, $requestDecoded->route()->parameter('user'));
    }
}