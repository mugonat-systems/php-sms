<?php

namespace Mugonat\Sms;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Mugonat\Container\Container;
use Mugonat\Container\Interfaces\ContainerExceptionInterface;
use Mugonat\Sms\Exceptions\NotConfiguredException;
use Mugonat\Sms\Exceptions\SmsResponseErrorException;
use Mugonat\Sms\Services\Aptus;
use Mugonat\Sms\Services\Bluedot;
use Mugonat\Sms\Services\Email;
use Mugonat\Sms\Services\File;
use Mugonat\Sms\Services\Infobip;
use Mugonat\Sms\Services\MessageBird;
use Mugonat\Sms\Services\Twilio;
use Throwable;
use function Mugonat\Container\dependency;

/**
 * Abstract class representing an SMS service handler.
 */
abstract class Sms
{
    public static string $default = Bluedot::class;

    /**
     * Sets the default service for message sending.
     *
     * @param string $service The service class name to set as default.
     * @return void
     */
    public static function setDefault(string $service): void
    {
        self::$default = $service;
    }

    /**
     * Sends a message to the specified phone number using the given service.
     *
     * @param string $phone The recipient's phone number.
     * @param string $message The message content to be sent.
     * @param mixed $service The service class to use for sending the message. Defaults to Bluedot::class.
     * @param bool $throw Determines whether to throw an exception on error. Defaults to true.
     * @return bool Indicates whether the message was sent successfully.
     * @throws Exception If an error occurs during sending and $throw is set to true.
     * @throws ContainerExceptionInterface|GuzzleException|Throwable
     */
    public static function send(string $phone, string $message, ?string $service = null, bool $throw = true): bool
    {
        $service ??= Sms::$default;
        $driver = Sms::driver($service);

        if (!$driver->isConfigured()) {
            throw new NotConfiguredException("Service driver '$service' not configured, please call Sms::configure($service, [config]) first");
        }

        $response = $driver->send($phone, $message);

        if ($response->hasError() && $throw) {
            throw new SmsResponseErrorException($response->error);
        }

        return $response->isSuccessful;
    }

    /**
     * Configures a service with the specified configuration settings.
     *
     * @template TService
     * @template-extends Service
     * @param class-string<TService> $service The name of the service to be configured.
     * @param array $config The configuration settings to apply to the service.
     * @return void
     */
    public static function configure(string $service, array $config): void
    {
        Container::instance()->set($service, function () use ($config, $service) {
            return Sms::driver($service, $config);
        });

        Sms::setDefault($service);
    }

    /**
     * Retrieves a service driver instance based on the specified name and configuration.
     *
     * @template TService
     * @param string|class-string<TService> $name The name of the service driver to retrieve.
     * @param array|null $config An optional configuration array for the service driver. Defaults to an empty array.
     * @return TService|Service The service driver instance.
     * @throws Throwable|ContainerExceptionInterface
     */
    public static function driver(string $name, ?array $config = null)
    {
        if ($config === null) {
            return dependency($name);
        }

        return match ($name) {
            Bluedot::$alias, Bluedot::class => new Bluedot($config),
            Infobip::$alias, Infobip::class => new Infobip($config),
            Aptus::$alias, Aptus::class => new Aptus($config),
            MessageBird::$alias, MessageBird::class => new MessageBird($config),
            Twilio::$alias, Twilio::class => new Twilio($config),
            File::$alias, File::class => new File($config),
            Email::$alias, Email::class => new Email($config),
            default => throw new Exception("Service driver '$name' not found")
        };
    }
}