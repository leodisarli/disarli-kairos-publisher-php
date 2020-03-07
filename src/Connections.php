<?php

namespace KairosPublisher;

use Predis\Client;

class Connections
{
    private $config;

    /**
     * Constructor
     * @param array $config
     */
    public function __construct(
        array $config
    ) {
        $this->config = $config;
    }

    /**
     * method createConnections
     * create all redis connections based on config prodived
     * @return array
     */
    public function createConnections() : array
    {
        $connections = [];
        foreach ($this->config as $key => $connection) {
            $connections[$key] = $this->newClient($connection);
        }
        return $connections;
    }

    /**
     * @codeCoverageIgnore
     * method newClient
     * create and return new Predis\Client object
     * (should not contain any logic, just instantiate the object and return it)
     * @param array $connection
     * @return Predis\Client
     */
    public function newClient(array $connection)
    {
        return new Client($connection);
    }
}
