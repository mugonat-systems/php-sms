<?php

use Mugonat\Sms\Services\MessageBird;
use Mugonat\Sms\Sms;

require __DIR__ . '/../vendor/autoload.php';

// Configuration
//Sms::configure(MessageBird::class, [
//    'access_key' => '<messagebird_access_key>',
//    'from_number' => '<messagebird_from_number>',
//]);

// Use
$success = \Mugonat\Sms\messageBirdSms('+212600000000', 'Hello World');

echo "Send sms response: $success";
