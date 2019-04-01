<?php

use KairosPublisher\Kairos;
use KairosPublisher\ValidateConfig;

class KairosTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \KairosPublisher\Kairos::connect
     */
    public function testConnect()  
    {
        $config = [
            [
                'host'   => 'localhost',
                'port'   => 6379,
            ],
            [
                'host'   => 'localhost',
                'port'   => 6380,
            ]
        ];
        $obj = new \stdClass();
        
        $kairosPartialMock = Mockery::mock(Kairos::class)->makePartial();
        $kairosPartialMock->shouldReceive('newConections')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf();

        $kairosPartialMock->shouldReceive('createConnections')
            ->once()
            ->withAnyArgs()
            ->andReturn([]);

        $kairosPartialMock->shouldReceive('newPublisher')
            ->once()
            ->withAnyArgs()
            ->andReturn($obj);

        $result = $kairosPartialMock->connect($config);
        $this->assertInternalType('bool', $result);
        $this->assertEquals(true, $result);
    }

    /**
     * @covers \KairosPublisher\Kairos::publish
     */
    public function testPublish()  
    {
        $config = [
            [
                'host'   => 'localhost',
                'port'   => 6379,
            ],
            [
                'host'   => 'localhost',
                'port'   => 6380,
            ]
        ];
        $obj = new \stdClass();
        $resultConnections = [
            $obj,
            $obj,
        ];
        $resultPublish = [
            'success',
            'success',
        ];
        $channel = 'test';
        $message = '{"data":"message"}';

        $kairosPartialMock = Mockery::mock(Kairos::class)->makePartial();
        $kairosPartialMock->shouldReceive('newConections')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf();

        $publisherMock = Mockery::mock(Publisher::class);
        $publisherMock->shouldReceive('publish')
            ->once()
            ->withAnyArgs()
            ->andReturn($resultPublish);

        $validateConfigMock = Mockery::mock(ValidateConfig::class);
        $validateConfigMock->shouldReceive('validate')
            ->once()
            ->withAnyArgs()
            ->andReturn(true);

        $kairosPartialMock->shouldReceive('createConnections')
            ->once()
            ->withAnyArgs()
            ->andReturn($resultConnections);

        $kairosPartialMock->shouldReceive('newPublisher')
            ->once()
            ->withAnyArgs()
            ->andReturn($publisherMock);

        $kairosPartialMock->shouldReceive('newValidateConfig')
            ->once()
            ->withNoArgs()
            ->andReturn($validateConfigMock);

        $kairosPartialMock->connect($config);
        $result = $kairosPartialMock->publish($channel, $message);
        $this->assertInternalType('array', $result);
        $this->assertEquals('success', $result[0]);
        $this->assertEquals('success', $result[1]);
    }

    /**
     * @covers \KairosPublisher\Kairos::publish
     * @expectedException KairosPublisher\Exception\NoPublisherException
     */
    public function testPublishEmptyPublisher()  
    {
        $channel = 'test';
        $message = '{"data":"message"}';

        $kairosPartialMock = Mockery::mock(Kairos::class)->makePartial();

        $result = $kairosPartialMock->publish($channel, $message);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
