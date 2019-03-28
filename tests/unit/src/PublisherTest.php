<?php

use Predis\Client;
use KairosPublisher\Publisher;

class PublisherTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \KairosPublisher\Publisher::__construct
     * @covers \KairosPublisher\Publisher::publish
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
        
        $connectionsMock = Mockery::mock(Client::class);
        $connectionsMock->shouldReceive('publish')
            ->twice()
            ->withAnyArgs()
            ->andReturn(true);
        $connections = [
            $connectionsMock,
            $connectionsMock,
        ];
        $publisher = new Publisher($connections);
        $result = $publisher->publish($channel, $message);
        $this->assertInternalType('bool', $result);
        $this->assertEquals(true, $result);
    }

    /**
     * @covers \KairosPublisher\Publisher::__construct
     * @covers \KairosPublisher\Publisher::publish
     */
    public function testPublishException()  
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
        
        $connectionsMock = Mockery::mock(Client::class);
        $connectionsMock->shouldReceive('publish')
            ->twice()
            ->withAnyArgs()
            ->andThrowExceptions([new \Exception()]);
        $connections = [
            $connectionsMock,
            $connectionsMock,
        ];
        $publisher = new Publisher($connections);
        $result = $publisher->publish($channel, $message);
        $this->assertInternalType('bool', $result);
        $this->assertEquals(true, $result);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
