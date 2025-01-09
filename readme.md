# Basic Sms Service

### Send SMSes with breeze

```php
\Mugonat\Sms\sms('+263XXX', 'Message is awesome');
```

You can find the full example [here](./example/index.php)

### Installation

Inside `composer.json` add this:
```json5
{
  // other composer things
  "minimum-stability": "dev",
  "prefer-stable": true,
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/mugonat-systems/php-sms"
    }
  ]
}
```

Then run 
```bash
composer require mugonat/sms
```

### Available drivers
You can find all available drivers [here](./src/Services)
