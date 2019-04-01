<?php

namespace KairosPublisher;

/**
 * class Publisher
 * Publish on messages on kairos channels
 */
class Publisher
{
    private $connections;

    /**
     * Constructor
     * @param array $connections
     */
    public function __construct(
        array $connections
    ) {
        $this->connections = $connections;
    }

    /**
     * method publish
     * public a message to a channel on redis
     * @param string $channel
     * @param string $message
     * @return array
     */
    public function publish(string $channel, string $message) : array
    {
        $result = [];
        foreach ($this->connections as $key => $connection) {
            try {
                $connection->publish($channel, $message);
                $result[$key] = $this->getResponse();
            } catch (\Exception $exception) {
                $result[$key] = $this->getResponse(false);
            }
        }
        return $result;
    }

    /**
     * method getResponse
     * add a response of publishing
     * @param bool $success
     * @return string
     */
    public function getResponse($success = true) : string
    {
        $status = 'success';
        if (!$success) {
            $status = 'fail';
        }
        return $status;
    }
}
