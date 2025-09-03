<?php

use Mugonat\Sms\Services\Bluedot;
use Mugonat\Sms\Sms;
use function Mugonat\Sms\bluedotSms;
use function Mugonat\Sms\serviceSms;
use function Mugonat\Sms\sms;

require __DIR__ . '/../vendor/autoload.php';

// Configuration
//Sms::configure(Bluedot::class, [
//    'id' => '<bluedot_api_id>',
//    'password' => '<bluedot_api_password>',
//    'sender_id' => '<bluedot_sender_id>',
//]);

// Use
$success = bluedotSms('+212600000000', 'Hello World');

echo "Send sms response: $success";
