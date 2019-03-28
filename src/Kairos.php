<?php

namespace KairosPublisher;

class Kairos
{
    private $publisher;

    public function connect($config)
    {
        $redis = $this->newConections($config);
        $connections = $redis->createConnections();
        $this->publisher = $this->newPublisher($connections);
        return true;
    }

    public function publish($channel, $message)
    {
        $this->publisher->publish($channel, $message);
        return true;
    }

    /**
    * @codeCoverageIgnore
    */
    public function newConections($config)
    {
        return new Connections($config);
    }

    /**
    * @codeCoverageIgnore
    */
    public function newPublisher($connections)
    {
        return new Publisher($connections);
    }
}
