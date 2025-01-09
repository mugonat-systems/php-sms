<?php

use Mugonat\Sms\Services\Bluedot;
use Mugonat\Sms\Sms;
use function Mugonat\Sms\bluedotSms;
use function Mugonat\Sms\serviceSms;
use function Mugonat\Sms\sms;

require __DIR__ . '/../vendor/autoload.php';

// Configuration
Sms::configure(Bluedot::class, [
    // service specific config
]);

// Direct service specification
Sms::send('+212600000000', 'Hello World', 'bluedot');
Sms::send('+212600000000', 'Hello World', Bluedot::class);
Sms::send('+212600000000', 'Hello World', Bluedot::$alias);

// Using a constant default
Sms::setDefault(Bluedot::class);
Sms::send('+212600000000', 'Hello World');
sms('+212600000000', 'Hello World');

// Direct service helper
serviceSms(Bluedot::class, '+212600000000', 'Hello World');
// Each available service has a dedicated helper
bluedotSms('+212600000000', 'Hello World');