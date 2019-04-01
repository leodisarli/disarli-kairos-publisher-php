<?php

namespace KairosPublisher;

use KairosPublisher\Exception\NoPublisherException;

/**
 * class Kairos
 * main class to publish messages on kairos channels
 */
class Kairos
{
    private $publisher = null;

    /**
     * method connect
     * connect to all redis provided by config array and set then to $this->publisher
     * @param array $config
     * @return bool
     */
    public function connect(array $config): bool
    {
        $validateConfig = $this->newValidateConfig();
        $validateConfig->validate($config);
        $redis = $this->newConections($config);
        $connections = $redis->createConnections();
        $this->publisher = $this->newPublisher($connections);
        return true;
    }

    /**
     * method publish
     * publish a message on a channel to all redis connections and return an array with
     *  results
     * @param string $channel
     * @param string $message
     * @return array
     */
    public function publish(string $channel, string $message): array
    {
        if (empty($this->publisher)) {
            throw new NoPublisherException();
        }
        return $this->publisher->publish($channel, $message);
    }

    /**
     * @codeCoverageIgnore
     * method newConnections
     * create and return new Connections object
     * (should not contain any logic, just instantiate the object and return it)
     * @param array $config
     * @return KairosPublisher\Connections
     */
    public function newConections(array $config)
    {
        return new Connections($config);
    }

    /**
     * @codeCoverageIgnore
     * method newPublisher
     * create and return new Publisher object
     * (should not contain any logic, just instantiate the object and return it)
     * @param array $connections
     * @return KairosPublisher\Publisher
     */
    public function newPublisher(array $connections)
    {
        return new Publisher($connections);
    }

    /**
     * @codeCoverageIgnore
     * method newValidateConfig
     * create and return new ValidateConfig object
     * (should not contain any logic, just instantiate the object and return it)
     * @return KairosPublisher\ValidateConfig
     */
    public function newValidateConfig()
    {
        return new ValidateConfig();
    }
}
