<?php

use Mugonat\Sms\Services\Mugonat;
use Mugonat\Sms\Sms;
use function Mugonat\Sms\mugonatSms;

require __DIR__ . '/../vendor/autoload.php';

// Configuration
//Sms::configure(Mugonat::class, [
//    'id' => '<mugonat_api_id>',
//    'key' => '<mugonat_api_key>',
//    'sender_id' => '<mugonat_sender_id>',
//]);

// Use
$success = mugonatSms('+212600000000', 'Hello World');

echo "Send sms response: $success";
