<?php

namespace Bondacom\Tests;

use Bondacom\LaravelHashids\RequestDecoder;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

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
     * @param $request
     * @param $uri
     * @param $method
     * @param array $parameters
     * @return $request
     */
    protected function simulateRequest($request, $uri, $method, array $parameters = [])
    {
        $request->setRouteResolver(function () use ($request, $uri, $method, $parameters) {
            return (new Route($method, $uri, $parameters))->bind($request);
        });
        
        return $request;
    }

    /**
     * @test
     */
    public function it_decode_headers_from_request()
    {
        $this->markTestIncomplete('Cannot simulate request for testing purpose.');

        /*$consumerId = 341;
        $request = new Request([], [], [], [], [], ['REQUEST_URI' => 'users']);
        $request->headers->replace([
            'Consumer-ID' => $this->encode($consumerId)
        ]);
        $this->simulateRequest($request, 'GET', 'users');

        $requestDecoded = $this->decoder->handle($request);

        $this->assertNotEquals($request->headers->all(), $requestDecoded->headers->all());
        $this->assertEquals($consumerId, $requestDecoded->headers->get('Consumer-ID'));*/
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
    public function it_decode_hash_ids()
    {
        $this->markTestIncomplete('Cannot simulate request for testing purpose.');
        /*$systemData = [
            'id' => 1,
            'name' => 'John',
            'email' => 'johndoe@gmail.com',
            'role_id' => 3,
            'provider_id' => null
        ];
        $encodedData = $this->converter->encode($systemData);

        $decodedData = $this->converter->decode($encodedData);

        $this->assertEquals($systemData, $decodedData);*/
    }
}