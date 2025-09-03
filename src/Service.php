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

    abstract public function send(string $phone, string $message, ?callable $modifyClientUsing = null): Response;

    abstract public function configure(array $config): static;

    abstract public function isConfigured(): bool;

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