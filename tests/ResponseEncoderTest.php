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

        $responseEncoded->assertStatus(200);
        $responseEncoded->assertJsonStructure([
            'id',
            'name',
            'email',
            'role_id',
        ]);
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
        
        $responseEncoded->assertJson([
            'name' => 'John',
            'email' => 'johndoe@gmail.com',
        ]);
        $responseEncoded->assertJsonMissing([
            'id' => 1,
            'role_id' => '3',
        ]);
    }
}