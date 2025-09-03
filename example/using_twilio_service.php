<?php

use Mugonat\Sms\Services\Twilio;
use Mugonat\Sms\Sms;

require __DIR__ . '/../vendor/autoload.php';

// Configuration
//Sms::configure(Twilio::class, [
//    'account_sid' => '<twilio_account_sid>',
//    'auth_token' => '<twilio_auth_token>',
//    'from_number' => '<twilio_from_number>',
//]);

// Use
$success = \Mugonat\Sms\twilioSms('+212600000000', 'Hello World');

echo "Send sms response: $success";

