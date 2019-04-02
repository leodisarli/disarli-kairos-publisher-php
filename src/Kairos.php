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
     * @param array $message
     * @return array
     */
    public function publish(string $channel, array $message): array
    {
        if (empty($this->publisher)) {
            throw new NoPublisherException();
        }
        $uuid = $this->generateUuid();
        $message = $this->addUuidToMessage($message, $uuid);
        $jsonMessage = json_encode($message);
        $result = $this->publisher->publish($channel, $jsonMessage);
        $result = [
            $uuid => $result,
        ];
        return $result;
    }

    /**
     * method addUuidToMessage
     * return the message with uuid
     * @return array
     */
    public function addUuidToMessage(array $message, string $uuid) : array
    {
        return [
            $uuid => $message,
        ];
    }

    /**
     * method generateUuid
     * generate an unique uuid
     * @return string
     */
    public function generateUuid() : string
    {
        $uuid = $this->newUuid();
        return $uuid->uuid4();
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

    /**
     * @codeCoverageIgnore
     * method newUuid
     * create and return new newUuid object
     * (should not contain any logic, just instantiate the object and return it)
     * @return KairosPublisher\Uuid
     */
    public function newUuid()
    {
        return new Uuid();
    }
}
