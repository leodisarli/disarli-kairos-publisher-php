<?php

namespace KairosPublisher;

use Predis\Client;

class Connections
{
    private $config;

    public function __construct(
        array $config
    ) {
        $this->config = $config;
    }

    public function createConnections()
    {
        $connections = [];
        foreach ($this->config as $key => $connection) {
            $connections[$key] = $this->newClient($connection);
        }
        return $connections;
    }

    /**
    * @codeCoverageIgnore
    */
    public function newClient($connection)
    {
        return new Client($connection);
    }
}
