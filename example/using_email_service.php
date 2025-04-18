<?php

use Mugonat\Sms\Services\Bluedot;
use Mugonat\Sms\Sms;
use function Mugonat\Sms\bluedotSms;
use function Mugonat\Sms\serviceSms;
use function Mugonat\Sms\sms;

require __DIR__ . '/../vendor/autoload.php';

// Configuration
Sms::configure(\Mugonat\Sms\Services\Email::class, [
    'host' => 'smtp.gmail.com',
    'username' => '',
    'password'  => '',
    'encryption' => 'tls',
    'port' => 587,
]);

var_dump(Sms::$default);

// Use
$success = \Mugonat\Sms\emailSms('jose@mugonat.com', 'Hello World');

echo "Send sms response: $success";
