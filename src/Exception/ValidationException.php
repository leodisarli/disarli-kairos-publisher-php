<?php

namespace KairosPublisher\Exception;

class ValidationException extends \Exception
{
    /**
     * Constructor
     * @param string $message
     */
    public function __construct($message)
    {
        parent::__construct($message, 422);
    }
}
