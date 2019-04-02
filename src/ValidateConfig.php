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
     * validate received config and return true
     * @param array $config
     * @return bool
     */
    public function validate(array $config)
    {
        $this->validateNotEmptyConfig($config);

        foreach ($config as $connect) {
            $this->validateHasKeys($connect);
            $this->validateNotEmptyKeys($connect);
            $this->validateUrl($connect['host']);
            $this->validatePort($connect['port']);
        }
        return true;
    }

    /**
     * method validateNotEmptyConfig
     * validate if received config is not empty
     * @param array $config
     * @throws KairosPublisher\ValidationException
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
     * validate if each connection on config has all needed keys
     * @param array $connect
     * @throws KairosPublisher\ValidationException
     * @return bool
     */
    public function validateHasKeys(array $connect)
    {
        if (!array_key_exists('host', $connect)) {
            $message = 'config missing host key';
            throw new ValidationException($message);
        }
        if (!array_key_exists('port', $connect)) {
            $message = 'config missing port key';
            throw new ValidationException($message);
        }
        return true;
    }

    /**
     * method validateNotEmptyKeys
     * validate if each connection key on config is not empty
     * @param array $connect
     * @throws KairosPublisher\ValidationException
     * @return bool
     */
    public function validateNotEmptyKeys(array $connect)
    {
        if (empty($connect['host'])) {
            $message = 'config empty host key';
            throw new ValidationException($message);
        }
        if (empty($connect['port'])) {
            $message = 'config empty port key';
            throw new ValidationException($message);
        }
        return true;
    }

    /**
     * method validateUrl
     * validate if host connection key on config is a valid url
     * @param string $url
     * @throws KairosPublisher\ValidationException
     * @return bool
     */
    public function validateUrl(string $url)
    {
        if ($url === 'localhost') {
            return true;
        }
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $message = "config url {$url} is not a valid url";
            throw new ValidationException($message);
        }
        return true;
    }

    /**
     * method validatePort
     * validate if port connection key on config is a valid port
     * @param int $port
     * @throws KairosPublisher\ValidationException
     * @return bool
     */
    public function validatePort(int $port)
    {
        if ($port < 6379 || $port > 6389) {
            $message = "config port {$port} must be a number between 6379 and 6389";
            throw new ValidationException($message);
        }
        return true;
    }
}
