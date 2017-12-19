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
        $checker = new Checker(['whitelist' => ['id']]);

        $this->assertTrue($checker->isAnId('id'));
        $this->assertTrue($checker->isAnId('role_id'));
        $this->assertTrue($checker->isAnId('Consumer-id'));
        $this->assertFalse($checker->isAnId('id_role'));
        $this->assertFalse($checker->isAnId('provider'));
    }

    /**
     * @test
     */
    public function it_check_with_id_keyname_as_default()
    {
        $checker = new Checker();

        $defaultKeyName = 'id';
        $this->assertEquals($defaultKeyName, $checker->keyName);
        $this->assertTrue($checker->isAnId($defaultKeyName));
    }

    /**
     * @test
     */
    public function it_check_if_field_is_an_id_with_keyname_as_token()
    {
        $keyName = 'token';
        $checker = new Checker(['whitelist' => [$keyName]]);

        $this->assertEquals($keyName, $checker->keyName);
        $this->assertTrue($checker->isAnId($keyName));
        $this->assertTrue($checker->isAnId('role_'.$keyName));
    }

    /**
     * @test
     */
    public function isInBlacklist_method()
    {
        $checker = new Checker(['whitelist' => ['id'], 'blacklist' => ['token']]);

        $this->assertTrue($checker->isInBlacklist('token'));
        $this->assertTrue($checker->isInBlacklist('role_token'));
        $this->assertTrue($checker->isInBlacklist('role-token'));

        $this->assertFalse($checker->isInBlacklist('id'));
        $this->assertFalse($checker->isInBlacklist('uuid'));
    }

    /**
     * @test
     */
    public function isInWhitelist_method()
    {
        $checker = new Checker(['whitelist' => ['id'], 'blacklist' => ['token']]);

        $this->assertTrue($checker->isInWhitelist('id'));
        $this->assertTrue($checker->isInWhitelist('role_id'));
        $this->assertTrue($checker->isInWhitelist('role-id'));

        $this->assertFalse($checker->isInWhitelist('token'));
        $this->assertFalse($checker->isInWhitelist('uuid'));
    }

    /**
     * @test
     */
    public function it_returns_whitelist_combinations()
    {
        $checker = new Checker(['whitelist' => ['id']]);

        $this->assertEquals(['id', '_id', '-id'], $checker->whitelist);
    }

    /**
     * @test
     */
    public function it_returns_blacklist_combinations()
    {
        $checker = new Checker(['blacklist' => ['user']]);

        $this->assertEquals(['user', '_user', '-user'], $checker->blacklist);
    }
}