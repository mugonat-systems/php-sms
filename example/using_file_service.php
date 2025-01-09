<?php

use Mugonat\Sms\Services\Bluedot;
use Mugonat\Sms\Services\File;
use Mugonat\Sms\Sms;
use function Mugonat\Container\dependency_exists;
use function Mugonat\Sms\bluedotSms;
use function Mugonat\Sms\fileSms;
use function Mugonat\Sms\serviceSms;
use function Mugonat\Sms\sms;

require __DIR__ . '/../vendor/autoload.php';

// Optional Configure
//Sms::configure(File::class, [
//    'directory' => __DIR__ . '/sms',
//    'file_name_format' => 'sms-{date}.log',
//    'file_date_format' => 'Y-m-d',
//]);

// Use
$success = fileSms('+212600000000', 'Hello World, from file service');

echo "Send sms response: $success";