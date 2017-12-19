<?php

namespace Bondacom\Tests;

use Bondacom\LaravelHashids\ResponseEncoder;
use Carbon\Carbon;

class ResponseEncoderTest extends TestCase
{
    /**
     * @var Bondacom\LaravelHashids\ResponseEncoder
     */
    public $encoder;

    public function setUp()
    {
        parent::setUp();

        $this->encoder = app(ResponseEncoder::class);
    }

    /**
     * @test
     */
    public function it_does_not_change_status_code_and_json_structure_from_response()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'John',
                'email' => 'johndoe@gmail.com',
                'role_id' => '3',
            ]
        ];
        $response = response(compact('data'), 200);

        $responseEncoded = $this->encoder->handle($response);

        $this->assertEquals(200, $responseEncoded->getStatusCode());
        $content = json_decode($responseEncoded->getContent(), true);

        $this->assertEquals(array_keys($content['data']), [
            'id',
            'name',
            'email',
            'role_id']
        );
    }

    /**
     * @test
     */
    public function it_encode_ids_from_response_content()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'John',
                'email' => 'johndoe@gmail.com',
                'role_id' => '3',
            ]
        ];
        $response = response(compact('data'), 200);

        $responseEncoded = $this->encoder->handle($response);
        $content = json_decode($responseEncoded->getContent(), true);

        $this->assertEquals($data['name'], $content['data']['name']);
        $this->assertEquals($data['email'], $content['data']['email']);
        $this->assertNotEquals($data['id'], $content['data']['id']);
        $this->assertNotEquals($data['role_id'], $content['data']['role_id']);
    }
}