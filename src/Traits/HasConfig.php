<?php

namespace Mugonat\Sms;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Mugonat\Sms\Response;
use Mugonat\Sms\Service;

/**
 * Represents the Bluedot service for sending SMS messages via the Bluedot API.
 * Implements the Service interface.
 */
trait HasConfig
{
    protected bool $configured = false;

    public function isConfigured(): bool
    {
        return $this->configured || $this->zeroConfig();
    }

    public abstract function zeroConfig(): bool;
}