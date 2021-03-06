<?php

namespace KairosPublisher;

use KairosPublisher\Exception\ValidationException;

/**
 * class ValidateConfig
 * validate received config via construct and throw exceptions when needed
 */
class ValidateConfig
{
    /**
     * method validate
     * validate received config call validations and return
     *  true if config its ok
     * @param array $config
     * @return bool
     */
    public function validate(array $config)
    {
        $this->validateNotEmptyConfig($config);

        foreach ($config as $connection) {
            $this->validateHasKeys($connection);
            $this->validateNotEmptyKeys($connection);
            $this->validatePort($connection['port']);
        }
        return true;
    }

    /**
     * method validateNotEmptyConfig
     * validate if received config is not empty,
     *  throw validation exception or return true
     * @param array $config
     * @throws KairosPublisher\Exception\ValidationException
     * @return bool
     */
    public function validateNotEmptyConfig(array $config)
    {
        if (empty($config)) {
            $message = 'config is empty';
            throw new ValidationException($message);
        }
        return true;
    }

    /**
     * method validateHasKeys
     * validate if each connection on config has all needed keys,
     *  throw validation exception or return true
     * @param array $connection
     * @throws KairosPublisher\Exception\ValidationException
     * @return bool
     */
    public function validateHasKeys(array $connection)
    {
        if (!array_key_exists('host', $connection)) {
            $message = 'config missing host key';
            throw new ValidationException($message);
        }
        if (!array_key_exists('port', $connection)) {
            $message = 'config missing port key';
            throw new ValidationException($message);
        }
        return true;
    }

    /**
     * method validateNotEmptyKeys
     * validate if each connection key on config is not empty,
     *  throw validation exception or return true
     * @param array $connection
     * @throws KairosPublisher\Exception\ValidationException
     * @return bool
     */
    public function validateNotEmptyKeys(array $connection)
    {
        if (empty($connection['host'])) {
            $message = 'config empty host key';
            throw new ValidationException($message);
        }
        if (empty($connection['port'])) {
            $message = 'config empty port key';
            throw new ValidationException($message);
        }
        return true;
    }

    /**
     * method validatePort
     * validate if port connection key on config is a valid port
     *  throw validation exception or return true
     * @param int $port
     * @throws KairosPublisher\Exception\ValidationException
     * @return bool
     */
    public function validatePort(int $port)
    {
        if ($port < 1 || $port > 65535) {
            $message = "config port {$port} must be a valid port";
            throw new ValidationException($message);
        }
        return true;
    }
}
