<?php

use Mugonat\Sms\Services\Bluedot;
use Mugonat\Sms\Sms;
use function Mugonat\Sms\bluedotSms;
use function Mugonat\Sms\serviceSms;
use function Mugonat\Sms\sms;

require __DIR__ . '/../vendor/autoload.php';

/*
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=chatbot.mugonat@gmail.com
MAIL_PASSWORD=tpzwcfxhzwjbusmb
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@pennypal.com
MAIL_FROM_NAME="${APP_NAME}"
 */

// Configuration
Sms::configure(\Mugonat\Sms\Services\Email::class, [
    'host' => 'smtp.gmail.com',
    'username' => 'chatbot.mugonat@gmail.com',
    'password'  => 'tpzwcfxhzwjbusmb',
    'encryption' => 'tls',
    'port' => 587,
]);

var_dump(Sms::$default);

// Use
$success = \Mugonat\Sms\emailSms('jose@mugonat.com', 'Hello World');

echo "Send sms response: $success";
