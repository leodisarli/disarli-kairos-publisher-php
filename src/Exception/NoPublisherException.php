<?php

namespace KairosPublisher\Exception;

class NoPublisherException extends \Exception
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(
            'no publisher set, call connect method before publish',
            500
        );
    }
}
