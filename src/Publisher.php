<?php

namespace KairosPublisher;

class Publisher
{
    private $connections;

    public function __construct(
        array $connections
    ) {
        $this->connections = $connections;
    }

    public function publish($channel, $message)
    {

        foreach ($this->connections as $key => $connection) {
            try {
                $connection->publish($channel, $message);
            } catch (\Exception $exception) {
            }
        }
        return true;
    }
}
