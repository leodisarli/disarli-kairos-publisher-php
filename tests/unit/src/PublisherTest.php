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
        $resultPublish = [
            'success',
            'success',
        ];
        $channel = 'test';
        $message = '{"data":"message"}';
        
        $connectionsMock = Mockery::mock(Client::class);
        $connectionsMock->shouldReceive('publish')
            ->twice()
            ->withAnyArgs()
            ->andReturn($resultPublish);
        $connections = [
            $connectionsMock,
            $connectionsMock,
        ];
        $publisher = new Publisher($connections);
        $result = $publisher->publish($channel, $message);
        $this->assertInternalType('array', $result);
        $this->assertEquals('success', $result[0]);
        $this->assertEquals('success', $result[1]);
    }

    /**
     * @covers \KairosPublisher\Publisher::__construct
     * @covers \KairosPublisher\Publisher::publish
     */
    public function testPublishException()
    {
        $resultPublish = [
            'fail',
            'fail',
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
        $this->assertInternalType('array', $result);
        $this->assertEquals('fail', $result[0]);
        $this->assertEquals('fail', $result[1]);
    }

    /**
     * @covers \KairosPublisher\Publisher::__construct
     * @covers \KairosPublisher\Publisher::getResponse
     */
    public function testGetResponse()
    {
        $connections = [];
        $publisher = new Publisher($connections);
        $result = $publisher->getResponse();
        $this->assertInternalType('string', $result);
        $this->assertEquals('success', $result);
    }

    /**
     * @covers \KairosPublisher\Publisher::__construct
     * @covers \KairosPublisher\Publisher::getResponse
     */
    public function testGetResponseFail()
    {
        $connections = [];
        $publisher = new Publisher($connections);
        $result = $publisher->getResponse(false);
        $this->assertInternalType('string', $result);
        $this->assertEquals('fail', $result);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
