<?php

use KairosPublisher\Kairos;

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
        $channel = 'test';
        $message = '{"data":"message"}';
        $obj = new \stdClass();

        $kairosPartialMock = Mockery::mock(Kairos::class)->makePartial();
        $kairosPartialMock->shouldReceive('newConections')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf();

        $publisherMock = Mockery::mock(Publisher::class);
        $publisherMock->shouldReceive('publish')
            ->once()
            ->withAnyArgs()
            ->andReturn(true);

        $kairosPartialMock->shouldReceive('createConnections')
            ->once()
            ->withAnyArgs()
            ->andReturn($obj);

        $kairosPartialMock->shouldReceive('newPublisher')
            ->once()
            ->withAnyArgs()
            ->andReturn($publisherMock);

        $kairosPartialMock->connect($config);
        $result = $kairosPartialMock->publish($channel, $message);
        $this->assertInternalType('bool', $result);
        $this->assertEquals(true, $result);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
