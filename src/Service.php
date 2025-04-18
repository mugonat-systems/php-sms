<?php

namespace Mugonat\Sms;

use GuzzleHttp\Exception\GuzzleException;
use Mugonat\Container\Interfaces\ContainerExceptionInterface;

abstract class Service
{
    public function __construct(array $config = [])
    {
        $this->configure($config);
    }

    public abstract function send(string $phone, string $message): Response;

    public abstract function configure(array $config): static;

    public abstract function isConfigured(): bool;

    /**
     * @throws ContainerExceptionInterface
     * @throws \Throwable
     * @throws GuzzleException
     */
    public static function to($phone, string $message): bool
    {
       return Sms::send($phone, $message, static::class, true);
    }
}