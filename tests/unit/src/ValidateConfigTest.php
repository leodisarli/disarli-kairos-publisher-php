<?php

use KairosPublisher\ValidateConfig;
use KairosPublisher\Exception\ValidationException;

class ValidateConfigTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \KairosPublisher\ValidateConfig::validate
     * @covers \KairosPublisher\ValidateConfig::validateNotEmptyConfig
     * @covers \KairosPublisher\ValidateConfig::validateHasKeys
     * @covers \KairosPublisher\ValidateConfig::validateNotEmptyKeys
     * @covers \KairosPublisher\ValidateConfig::validatePort
     */
    public function testValidateTrue()
    {
        $config = [
            [
                'host' => 'localhost',
                'port' => 6379,
            ],
            [
                'host' => 'localhost',
                'port' => 6380,
            ]
        ];

        $validateConfig = new ValidateConfig();

        $result = $validateConfig->validate($config);
        $this->assertInternalType('bool', $result);
        $this->assertEquals(true, $result);
    }

    /**
     * @covers \KairosPublisher\ValidateConfig::validateNotEmptyConfig
     * @expectedException \KairosPublisher\Exception\ValidationException
     * @expectedExceptionCode 422
     * @expectedExceptionMessage config is empty
     */
    public function testValidateNotEmptyConfigFalse()
    {
        $config = [];
        $validateConfig = new ValidateConfig();
        $validateConfig->validateNotEmptyConfig($config);
    }

    /**
     * @covers \KairosPublisher\ValidateConfig::validateHasKeys
     * @expectedException \KairosPublisher\Exception\ValidationException
     * @expectedExceptionCode 422
     * @expectedExceptionMessage config missing host key
     */
    public function testValidateHasKeysMissingHost()
    {
        $connect = [
            'port' => 6379,
        ];
        $validateConfig = new ValidateConfig();
        $validateConfig->validateHasKeys($connect);
    }

    /**
     * @covers \KairosPublisher\ValidateConfig::validateHasKeys
     * @expectedException \KairosPublisher\Exception\ValidationException
     * @expectedExceptionCode 422
     * @expectedExceptionMessage config missing port key
     */
    public function testValidateHasKeysMissingPort()
    {
        $connect = [
            'host' => 'localhost',
        ];
        $validateConfig = new ValidateConfig();
        $validateConfig->validateHasKeys($connect);
    }

    /**
     * @covers \KairosPublisher\ValidateConfig::validateNotEmptyKeys
     * @expectedException \KairosPublisher\Exception\ValidationException
     * @expectedExceptionCode 422
     * @expectedExceptionMessage config empty host key
     */
    public function testValidateNotEmptyKeysHost()
    {
        $connect = [
            'host' => '',
            'port' => 1,
        ];
        $validateConfig = new ValidateConfig();
        $validateConfig->validateNotEmptyKeys($connect);
    }

    /**
     * @covers \KairosPublisher\ValidateConfig::validateNotEmptyKeys
     * @expectedException \KairosPublisher\Exception\ValidationException
     * @expectedExceptionCode 422
     * @expectedExceptionMessage config empty port key
     */
    public function testValidateNotEmptyKeysPort()
    {
        $connect = [
            'host' => 'localhost',
            'port' => '',
        ];
        $validateConfig = new ValidateConfig();
        $validateConfig->validateNotEmptyKeys($connect);
    }

    /**
     * @covers \KairosPublisher\ValidateConfig::validatePort
     * @expectedException \KairosPublisher\Exception\ValidationException
     * @expectedExceptionCode 422
     * @expectedExceptionMessage config port -1 must be a valid port
     */
    public function testValidatePort()
    {
        $port = -1;
        $validateConfig = new ValidateConfig();
        $validateConfig->validatePort($port);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
