<?php

namespace Bondacom\Tests;

use Bondacom\LaravelHashids\Converter;
use Carbon\Carbon;

class ConverterTest extends TestCase
{
    /**
     * @var Bondacom\LaravelHashids\Converter
     */
    public $converter;

    public function setUp()
    {
        parent::setUp();

        $this->converter = app(Converter::class);
    }

    /**
     * @test
     */
    public function it_encode_system_ids()
    {
        $systemData = [
            'id' => 1,
            'name' => 'John',
            'email' => 'johndoe@gmail.com',
            'role_id' => 3,
            'provider_id' => null,
            'orders' => [
                'id' => 11,
                'cupon_id' => 2,
                'created_at' => Carbon::now()
            ]
        ];

        $encodedData = $this->converter->encode($systemData);

        $this->assertNotEquals($systemData['id'], $encodedData['id']);
        $this->assertEquals($systemData['name'], $encodedData['name']);
        $this->assertEquals($systemData['email'], $encodedData['email']);
        $this->assertNotEquals($systemData['role_id'], $encodedData['role_id']);
        $this->assertEquals($systemData['provider_id'], $encodedData['provider_id']);
        $this->assertNotEquals($systemData['orders']['id'], $encodedData['orders']['id']);
        $this->assertNotEquals($systemData['orders']['cupon_id'], $encodedData['orders']['cupon_id']);
        $this->assertEquals($systemData['orders']['created_at'], $encodedData['orders']['created_at']);
    }

    /**
     * @test
     */
    public function it_encode_arrays_without_keys()
    {
        $data = [
            'data' => [
                'First data',
                'Second data',
            ],
            'meta' => [
                'First meta'
            ]
        ];

        $encodedData = $this->converter->encode($data);
        $this->assertEquals($data, $encodedData);
    }

    /**
     * @test
     */
    public function it_decode_hash_ids()
    {
        $systemData = [
            'id' => 1,
            'name' => 'John',
            'email' => 'johndoe@gmail.com',
            'role_id' => 3,
            'provider_id' => null
        ];
        $encodedData = $this->converter->encode($systemData);

        $decodedData = $this->converter->decode($encodedData);

        $this->assertEquals($systemData, $decodedData);
    }
}