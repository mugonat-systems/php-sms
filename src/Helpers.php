<?php

namespace Mugonat\Sms;

use GuzzleHttp\Exception\GuzzleException;
use Mugonat\Container\Container;
use Mugonat\Container\Interfaces\ContainerExceptionInterface;
use Mugonat\Sms\Services\Bluedot;
use Mugonat\Sms\Services\Email;
use Mugonat\Sms\Services\File;
use function Mugonat\Container\dependency_exists;

if (!function_exists('sms')) {
    /**
     * Sends an SMS message.
     *
     * @param string $phone The recipient's phone number.
     * @param string $message The message content to be sent.
     *
     * @return bool Returns true if the message was sent successfully, otherwise false.
     * @throws GuzzleException
     */
    function sms(string $phone, string $message): bool
    {
        return Sms::send($phone, $message, throw: false);
    }
}

if (!function_exists('serviceSms')) {
    /**
     * Sends an SMS message using the specified service.
     *
     * @param string $service The name of the SMS service to be used.
     * @param string $phone The recipient's phone number.
     * @param string $message The message content to be sent.
     *
     * @return bool Returns true if the message was sent successfully, otherwise false.
     * @throws GuzzleException
     */
    function serviceSms(string $service, string $phone, string $message): bool
    {
        return Sms::send($phone, $message, $service, false);
    }
}


if (!function_exists('bluedotSms')) {
    /**
     * Sends an SMS message using the Bluedot service.
     *
     * @param string $phone The recipient's phone number.
     * @param string $message The message content to be sent.
     *
     * @return bool Returns true if the message was sent successfully, otherwise false.
     * @throws GuzzleException
     */
    function bluedotSms(string $phone, string $message): bool
    {
        if (!Container::instance()->exists(Bluedot::class)) {
            Sms::configure(Bluedot::class, []);
        }

        return Sms::send($phone, $message, Bluedot::class, false);
    }
}

if (!function_exists('emailSms')) {
    /**
     * Sends an SMS message using the Bluedot service.
     *
     * @param string $email The recipient's email address.
     * @param string $message The message content to be sent.
     *
     * @return bool Returns true if the message was sent successfully, otherwise false.
     * @throws GuzzleException
     */
    function emailSms(string $email, string $message): bool
    {
        if (!Container::instance()->exists(Email::class)) {
            Sms::configure(Email::class, []);
        }

        return Sms::send($email, $message, Email::class, false);
    }
}


if (!function_exists('fileSms')) {
    /**
     * Sends an SMS message using the File service.
     *
     * @param string $phone The recipient's phone number.
     * @param string $message The message content to be sent.
     *
     * @return bool Returns true if the message was sent successfully, otherwise false.
     * @throws GuzzleException
     */
    function fileSms(string $phone, string $message): bool
    {
        if (!Container::instance()->exists(File::class)) {
            Sms::configure(File::class, []);
        }

        return Sms::send($phone, $message, File::class, false);
    }
}