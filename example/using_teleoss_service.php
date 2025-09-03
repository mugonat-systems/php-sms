<?php

use Mugonat\Sms\Services\Teleoss;
use Mugonat\Sms\Sms;

require __DIR__ . '/../vendor/autoload.php';

// Configuration
//Sms::configure(Teleoss::class, [
//    'api_key' => '<teleoss_api_key>',
//    'sender_id' => '<teleoss_sender_id>',
//    'domain' => '<teleoss_domain>',
//]);

// Use
$success = \Mugonat\Sms\teleossSms('+212600000000', 'Hello World');

echo "Send sms response: $success";

