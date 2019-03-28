<?php

use KairosPublisher\Connections;

class ConnectionsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \KairosPublisher\Connections::__construct
     * @covers \KairosPublisher\Connections::createConnections
     */
    public function testCreateConnections()  
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
        $client = new \stdClass();
        $connectionsPartialMock = Mockery::mock(Connections::class, $config)->makePartial();
        $connectionsPartialMock->shouldReceive('newClient')
            ->twice()
            ->withAnyArgs()
            ->andReturn($client);
        $result = $connectionsPartialMock->createConnections();
        $this->assertInternalType('array', $result);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
