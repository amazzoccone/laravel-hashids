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
        $config = [
            'separators' => ['_', '-'],
            'whitelist' => ['id'],
            'blacklist' => []
        ];
        $checker = new Checker($config);

        $this->assertTrue($checker->isAnId('id'));
        $this->assertTrue($checker->isAnId('role_id'));
        $this->assertTrue($checker->isAnId('Consumer-id'));
        $this->assertFalse($checker->isAnId('id_role'));
        $this->assertFalse($checker->isAnId('provider'));
        $this->assertFalse($checker->isAnId(1));
    }

    /**
     * @test
     */
    public function it_check_if_field_is_an_id_with_keyname_as_token()
    {
        $config = [
            'separators' => ['_', '-'],
            'whitelist' => ['token'],
            'blacklist' => []
        ];
        $checker = new Checker($config);

        $this->assertTrue($checker->isAnId('token'));
        $this->assertTrue($checker->isAnId('role_token'));
    }

    /**
     * @test
     */
    public function isInBlacklist_method()
    {
        $config = [
            'separators' => ['_', '-'],
            'whitelist' => ['id'],
            'blacklist' => ['token']
        ];
        $checker = new Checker($config);

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
        $config = [
            'separators' => ['_', '-'],
            'whitelist' => ['id'],
            'blacklist' => ['token']
        ];
        $checker = new Checker($config);

        $this->assertTrue($checker->isInWhitelist('id'));
        $this->assertTrue($checker->isInWhitelist('role_id'));
        $this->assertTrue($checker->isInWhitelist('role-id'));

        $this->assertFalse($checker->isInWhitelist('token'));
        $this->assertFalse($checker->isInWhitelist('uuid'));
    }

    /**
     * @test
     */
    public function isInWhitelist_method_returns_true_if_whitelist_config_is_true()
    {
        $config = [
            'separators' => [],
            'whitelist' => true,
            'blacklist' => ['token']
        ];
        $checker = new Checker($config);

        $this->assertTrue($checker->isInWhitelist('id'));
        $this->assertTrue($checker->isInWhitelist('user'));
        $this->assertTrue($checker->isInWhitelist('test'));
    }

    /**
     * @test
     */
    public function isInWhitelist_method_returns_false_if_whitelist_config_is_false()
    {
        $config = [
            'separators' => [],
            'whitelist' => false,
            'blacklist' => []
        ];
        $checker = new Checker($config);

        $this->assertFalse($checker->isInWhitelist('id'));
        $this->assertFalse($checker->isInWhitelist('user'));
        $this->assertFalse($checker->isInWhitelist('test'));
    }

    /**
     * @test
     */
    public function it_returns_whitelist_collection()
    {
        $config = [
            'separators' => ['_', '-'],
            'whitelist' => ['id'],
            'blacklist' => ['token']
        ];

        $checker = new Checker($config);

        $this->assertEquals(collect(['id']), $checker->whitelist);
    }

    /**
     * @test
     */
    public function it_returns_blacklist_collection()
    {
        $config = [
            'separators' => ['_', '-'],
            'whitelist' => ['id'],
            'blacklist' => ['user']
        ];

        $checker = new Checker($config);

        $this->assertEquals(collect(['user']), $checker->blacklist);
    }
}