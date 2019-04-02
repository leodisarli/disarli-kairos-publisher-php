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
        $message = [
            'data' => 'message'
        ];
        $uuid = 'a7ad790d-fad1-4c2e-8fa9-e0f0565af546';

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

        $uuidMock = Mockery::mock(Uuid::class);
        $uuidMock->shouldReceive('uuid4')
            ->once()
            ->withNoArgs()
            ->andReturn($uuid);

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

        $kairosPartialMock->shouldReceive('newUuid')
            ->once()
            ->withNoArgs()
            ->andReturn($uuidMock);

        $kairosPartialMock->connect($config);
        $result = $kairosPartialMock->publish($channel, $message);
        $this->assertInternalType('array', $result);
        $this->assertEquals('success', $result[$uuid][0]);
        $this->assertEquals('success', $result[$uuid][1]);
    }

    /**
     * @covers \KairosPublisher\Kairos::publish
     * @expectedException KairosPublisher\Exception\NoPublisherException
     * @expectedExceptionCode 500
     * @expectedExceptionMessage no publisher set, call connect method before publish
     */
    public function testPublishEmptyPublisher()
    {
        $channel = 'test';
        $message = [
            'data' => 'message'
        ];

        $kairosPartialMock = Mockery::mock(Kairos::class)->makePartial();
        $kairosPartialMock->publish($channel, $message);
    }

    /**
     * @covers \KairosPublisher\Kairos::addUuidToMessage
     */
    public function testAddUuidToMessage()
    {
        $uuid = 'a7ad790d-fad1-4c2e-8fa9-e0f0565af546';
        $message = [
            'data' => 'message'
        ];

        $kairos = new Kairos();
        $result = $kairos->addUuidToMessage($message, $uuid);
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey($uuid, $result);
    }

    /**
     * @covers \KairosPublisher\Kairos::generateUuid
     */
    public function testGenerateUuid()
    {
        $uuid = 'a7ad790d-fad1-4c2e-8fa9-e0f0565af546';
        $uuidMock = Mockery::mock(Uuid::class);
        $uuidMock->shouldReceive('uuid4')
            ->once()
            ->withNoArgs()
            ->andReturn($uuid);
        
        $kairosPartialMock = Mockery::mock(Kairos::class)->makePartial();
        $kairosPartialMock->shouldReceive('newUuid')
            ->once()
            ->withNoArgs()
            ->andReturn($uuidMock);

        $result = $kairosPartialMock->generateUuid();
        $this->assertInternalType('string', $result);
        $this->assertEquals($uuid, $result);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
