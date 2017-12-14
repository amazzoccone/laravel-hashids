<?php

namespace Bondacom\Tests;

use Bondacom\LaravelHashids\Checker;
use Carbon\Carbon;

class CheckerTest extends TestCase
{
    /**
     * @test
     */
    public function it_check_if_field_is_an_id()
    {
        $checker = new Checker('id');

        $this->assertTrue($checker->isAnId('id'));
        $this->assertTrue($checker->isAnId('role_id'));
        $this->assertFalse($checker->isAnId('id_role'));
        $this->assertFalse($checker->isAnId('provider'));
    }

    /**
     * @test
     */
    public function it_check_with_id_keyname_as_default()
    {
        $defaultKeyName = 'id';
        $checker = new Checker();

        $this->assertEquals($defaultKeyName, $checker->keyName);
        $this->assertTrue($checker->isAnId($defaultKeyName));
    }

    /**
     * @test
     */
    public function it_check_if_field_is_an_id_with_keyname_as_token()
    {
        $checker = new Checker('token');

        $this->assertTrue($checker->isAnId('token'));
        $this->assertTrue($checker->isAnId('role_token'));
    }
}