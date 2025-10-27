<?php

namespace Mugonat\Sms;

use GuzzleHttp\Exception\GuzzleException;
use Mugonat\Container\Container;
use Mugonat\Container\Interfaces\ContainerExceptionInterface;
use Mugonat\Sms\Services\Aptus;
use Mugonat\Sms\Services\Bluedot;
use Mugonat\Sms\Services\Email;
use Mugonat\Sms\Services\File;
use Mugonat\Sms\Services\Infobip;
use Mugonat\Sms\Services\MessageBird;
use Mugonat\Sms\Services\Mugonat;
use Mugonat\Sms\Services\Teleoss;
use Mugonat\Sms\Services\Twilio;

if (!function_exists('sms')) {
    /**
     * Sends an SMS message.
     *
     * @param string $phone The recipient's phone number.
     * @param string $message The message content to be sent.
     *
     * @return bool Returns true if the message was sent successfully, otherwise false.
     * @throws \Throwable
     */
    function sms(string $phone, string $message, $throw = false): bool
    {
        return Sms::send($phone, $message, throw: $throw);
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
     * @throws ContainerExceptionInterface
     * @throws \Throwable
     */
    function serviceSms(string $service, string $phone, string $message): bool
    {
        return Sms::send($phone, $message, $service, false);
    }
}


if (!function_exists('mugonatSms')) {
    /**
     * Sends an SMS message using the Mugonat service.
     *
     * @param string $phone The recipient's phone number.
     * @param string $message The message content to be sent.
     *
     * @return bool Returns true if the message was sent successfully, otherwise false.
     * @throws ContainerExceptionInterface
     * @throws GuzzleException
     * @throws \Throwable
     */
    function mugonatSms(string $phone, string $message): bool
    {
        if (!Container::instance()->exists(Mugonat::class)) {
            Sms::configure(Mugonat::class, []);
        }

        return Sms::send($phone, $message, Mugonat::class, false);
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
     * @throws ContainerExceptionInterface
     * @throws GuzzleException
     * @throws \Throwable
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

if (!function_exists('aptusSms')) {
    /**
     * Sends an SMS message using the Aptus service.
     *
     * @param string $phone The recipient's phone number.
     * @param string $message The message content to be sent.
     *
     * @return bool Returns true if the message was sent successfully, otherwise false.
     * @throws GuzzleException
     */
    function aptusSms(string $phone, string $message): bool
    {
        if (!Container::instance()->exists(Aptus::class)) {
            Sms::configure(Aptus::class, []);
        }

        return Sms::send($phone, $message, Aptus::class, false);
    }
}

if (!function_exists('infobipSms')) {
    /**
     * Sends an SMS message using the Infobip service.
     *
     * @param string $phone The recipient's phone number.
     * @param string $message The message content to be sent.
     *
     * @return bool Returns true if the message was sent successfully, otherwise false.
     * @throws GuzzleException
     */
    function infobipSms(string $phone, string $message): bool
    {
        if (!Container::instance()->exists(Infobip::class)) {
            Sms::configure(Infobip::class, []);
        }

        return Sms::send($phone, $message, Infobip::class, false);
    }
}

if (!function_exists('messageBirdSms')) {
    /**
     * Sends an SMS message using the MessageBird service.
     *
     * @param string $phone The recipient's phone number.
     * @param string $message The message content to be sent.
     *
     * @return bool Returns true if the message was sent successfully, otherwise false.
     * @throws GuzzleException
     */
    function messageBirdSms(string $phone, string $message): bool
    {
        if (!Container::instance()->exists(MessageBird::class)) {
            Sms::configure(MessageBird::class, []);
        }

        return Sms::send($phone, $message, MessageBird::class, false);
    }
}

if (!function_exists('teleossSms')) {
    /**
     * Sends an SMS message using the Teleoss service.
     *
     * @param string $phone The recipient's phone number.
     * @param string $message The message content to be sent.
     *
     * @return bool Returns true if the message was sent successfully, otherwise false.
     * @throws GuzzleException
     */
    function teleossSms(string $phone, string $message): bool
    {
        if (!Container::instance()->exists(Teleoss::class)) {
            Sms::configure(Teleoss::class, []);
        }

        return Sms::send($phone, $message, Teleoss::class, false);
    }
}

if (!function_exists('twilioSms')) {
    /**
     * Sends an SMS message using the Twilio service.
     *
     * @param string $phone The recipient's phone number.
     * @param string $message The message content to be sent.
     *
     * @return bool Returns true if the message was sent successfully, otherwise false.
     * @throws GuzzleException
     */
    function twilioSms(string $phone, string $message): bool
    {
        if (!Container::instance()->exists(Twilio::class)) {
            Sms::configure(Twilio::class, []);
        }

        return Sms::send($phone, $message, Twilio::class, false);
    }
}
