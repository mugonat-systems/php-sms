# Basic Sms Service

### Send SMS with breeze

```php
\Mugonat\Sms\sms('+263XXX', 'Message is awesome');
```

You can find the full example [here](./example/index.php)

### Installation

```bash
composer require mugonat/sms
```

### Available drivers
You can find all available drivers [here](./src/Services)

### Laravel Integration

Add a config inside `config/sms.php` with contents:
```php
<?php

return [
    'driver' => env('SMS_DRIVER', 'file'), // file, email, mugonat, bluedot, infobip, email, teleoss

    'drivers' => [
        \Mugonat\Sms\Services\File::$alias => [
            'driver' => \Mugonat\Sms\Services\File::class,
            'directory' => env('SMS_FILE_DIRECTORY', storage_path('logs')),
        ],
        
        \Mugonat\Sms\Services\Mugonat::$alias => [
            'driver' => \Mugonat\Sms\Services\Mugonat::class,
            'id' => env('SMS_MUGONAT_API_ID'),
            'key' => env('SMS_MUGONAT_API_KEY'),
            'sender_id' => env('SMS_MUGONAT_API_SENDER_ID'),
        ],

        \Mugonat\Sms\Services\Bluedot::$alias => [
            'driver' => \Mugonat\Sms\Services\Bluedot::class,
            'api_id' => env('SMS_BLUEDOT_API_ID'),
            'api_password' => env('SMS_BLUEDOT_API_PASSWORD'),
            'sender_id' => env('SMS_BLUEDOT_API_SENDER_ID'),
        ],

        \Mugonat\Sms\Services\Infobip::$alias => [
            'driver' => \Mugonat\Sms\Services\Infobip::class,
            'host' => env('SMS_INFOBIP_HOST'),
            'senderName' => env('SMS_INFOBIP_SENDER_NAME'),
            'apiKey' => env('SMS_INFOBIP_API_KEY'),
        ],

        \Mugonat\Sms\Services\Teleoss::$alias => [
            'driver' => \Mugonat\Sms\Services\Teleoss::class,
            'api_key' => env('SMS_TELEOSS_API_KEY'),
            'sender_id' => env('SMS_TELEOSS_SENDER_ID'),
            'domain' => env('SMS_TELEOSS_DOMAIN'),
        ],

        'email' => [
            'driver' => \Mugonat\Sms\Services\Email::class,
            'host' => env('SMS_HOST'),
            'port' => env('SMS_PORT'),
            'username' => env('SMS_USERNAME'),
            'password' => env('SMS_PASSWORD'),
            'encryption' => env('SMS_ENCRYPTION'),
            'from' => env('SMS_FROM'),
            'fromName' => env('SMS_FROM_NAME'),
        ],
    ],
];
```

Then inside `app/Providers/AppServiceProvider.php` add 
```php
    public function register(): void
    {
        $this->configureSms();
    }

    public function configureSms(): void
    {
        $driver = config('sms.driver');
        $config = config("sms.drivers.$driver");

        $service = Arr::pull($config, 'driver');

        Sms::configure($service, $config);
    }
```