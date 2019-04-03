<?php

namespace KairosPublisher;

/**
 * class Publisher
 * publish on messages to kairos channels on each available redis
 */
class Publisher
{
    private $connections;

    /**
     * constructor
     * @param array $connections
     */
    public function __construct(
        array $connections
    ) {
        $this->connections = $connections;
    }

    /**
     * method publish
     * public a message to a channel on each available redis
     * @param string $channel
     * @param string $message
     * @return array
     */
    public function publish(
        string $channel,
        string $message
    ) : array {
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
     * return the response of publishing
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
