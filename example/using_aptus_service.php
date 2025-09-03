<?php

use Mugonat\Sms\Services\Aptus;
use Mugonat\Sms\Sms;

require __DIR__ . '/../vendor/autoload.php';

// Configuration
//Sms::configure(Aptus::class, [
//    'username' => '<aptus_username>',
//    'password' => '<aptus_password>',
//    'sender_id' => '<aptus_sender_id>',
//]);

// Use
$success = \Mugonat\Sms\aptusSms('+212600000000', 'Hello World');

echo "Send sms response: $success";
