<?php

use Mugonat\Sms\Services\Infobip;
use Mugonat\Sms\Sms;

require __DIR__ . '/../vendor/autoload.php';

// Configuration
//Sms::configure(Infobip::class, [
//    'host' => '<infobip_host>',
//    'senderName' => '<infobip_sender_name>',
//    'apiKey' => '<infobip_api_key>',
//]);

// Use
$success = \Mugonat\Sms\infobipSms('+212600000000', 'Hello World');

echo "Send sms response: $success";
