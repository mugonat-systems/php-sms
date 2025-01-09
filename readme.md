# Basic Service Container

### Resolve dependencies like a pro

```php
// Old way, DON'T DO THIS
$instance = new User(new Mail(new MailSender()));
$instance->sendMail("alex (Using old instantiation)");

// New way, cool way
$instance = dependency(User::class);
$instance->sendMail("alex (Using dependency helper)");
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
      "url": "https://github.com/mugonat-systems/php-service-container"
    }
  ]
}
```

Then run 
```bash
composer require mugonat/service-container
```