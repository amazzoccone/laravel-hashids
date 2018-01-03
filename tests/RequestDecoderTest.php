<?php

namespace Bondacom\LaravelHashids\Tests;

use Bondacom\LaravelHashids\RequestDecoder;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class RequestDecoderTest extends TestCase
{
    /**
     * @var RequestDecoder
     */
    protected $decoder;

    /**
     * @var Request
     */
    protected $request;

    public function setUp()
    {
        parent::setUp();

        $this->decoder = app(RequestDecoder::class);
        $this->request = $this->newRequest();
    }

    /**
     * Get new request
     *
     * @return Request
     */
    protected function newRequest()
    {
        $request = Request::create('testing/' . $this->encode(1), 'POST');

        $request->setRouteResolver(function () use ($request) {
            return (new Route('POST', 'testing/{user}', []))->bind($request);
        });

        return $request;
    }

    /**
     * @test
     */
    public function it_decode_headers_from_request()
    {
        $consumerId = 341;
        $this->request->headers->replace([
            'Consumer-ID' => $this->encode($consumerId)
        ]);

        $this->decoder->handle($this->request);

        $this->assertEquals($consumerId, $this->request->headers->get('Consumer-ID'));
    }

    /**
     * @test
     */
    public function it_decode_route_parameters_from_request()
    {
        $this->markTestIncomplete('Cannot simulate request for testing purpose.');

        /*$userId = 341;
        $request = new Request([], [], [], [], [], ['REQUEST_URI' => 'users/'.$userId]);
        $this->simulateRequest($request, 'GET', 'users/{user}');

        $requestDecoded = $this->decoder->handle($request);

        $this->assertNotEquals($request->route()->parameters(), $requestDecoded->route()->parameters());
        $this->assertEquals($userId, $requestDecoded->route()->parameter('user'));*/
    }

    /**
     * @test
     */
    public function it_decode_query_and_post_parameters_hash_ids()
    {
        $this->request->replace([
            'id' => $this->encode(12),
            'name' => 'Pepe',
            'orders_id' => [$this->encode(8), $this->encode(9), $this->encode(11)],
            'gender' => [
                'id' => $this->encode(2),
                'description' => 'F'
            ]
        ]);

        $this->decoder->handle($this->request);

        $this->assertEquals([
            'id' => 12,
            'name' => 'Pepe',
            'orders_id' => [8, 9, 11],
            'gender' => [
                'id' => 2,
                'description' => 'F'
            ]
        ], $this->request->all());
    }
}