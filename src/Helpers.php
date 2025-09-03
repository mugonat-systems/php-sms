

if (!function_exists('aptusSms')) {
    /**
     * Sends an SMS message using the Aptus service.
     *
     * @param string $phone The recipient's phone number.
     * @param string $message The message content to be sent.
     *
     * @return bool Returns true if the message was sent successfully, otherwise false.
     * @throws GuzzleException
     */
    function aptusSms(string $phone, string $message): bool
    {
        if (!Container::instance()->exists(Aptus::class)) {
            Sms::configure(Aptus::class, []);
        }

        return Sms::send($phone, $message, Aptus::class, false);
    }
}

if (!function_exists('infobipSms')) {
    /**
     * Sends an SMS message using the Infobip service.
     *
     * @param string $phone The recipient's phone number.
     * @param string $message The message content to be sent.
     *
     * @return bool Returns true if the message was sent successfully, otherwise false.
     * @throws GuzzleException
     */
    function infobipSms(string $phone, string $message): bool
    {
        if (!Container::instance()->exists(Infobip::class)) {
            Sms::configure(Infobip::class, []);
        }

        return Sms::send($phone, $message, Infobip::class, false);
    }
}

if (!function_exists('messageBirdSms')) {
    /**
     * Sends an SMS message using the MessageBird service.
     *
     * @param string $phone The recipient's phone number.
     * @param string $message The message content to be sent.
     *
     * @return bool Returns true if the message was sent successfully, otherwise false.
     * @throws GuzzleException
     */
    function messageBirdSms(string $phone, string $message): bool
    {
        if (!Container::instance()->exists(MessageBird::class)) {
            Sms::configure(MessageBird::class, []);
        }

        return Sms::send($phone, $message, MessageBird::class, false);
    }
}

if (!function_exists('teleossSms')) {
    /**
     * Sends an SMS message using the Teleoss service.
     *
     * @param string $phone The recipient's phone number.
     * @param string $message The message content to be sent.
     *
     * @return bool Returns true if the message was sent successfully, otherwise false.
     * @throws GuzzleException
     */
    function teleossSms(string $phone, string $message): bool
    {
        if (!Container::instance()->exists(Teleoss::class)) {
            Sms::configure(Teleoss::class, []);
        }

        return Sms::send($phone, $message, Teleoss::class, false);
    }
}

if (!function_exists('twilioSms')) {
    /**
     * Sends an SMS message using the Twilio service.
     *
     * @param string $phone The recipient's phone number.
     * @param string $message The message content to be sent.
     *
     * @return bool Returns true if the message was sent successfully, otherwise false.
     * @throws GuzzleException
     */
    function twilioSms(string $phone, string $message): bool
    {
        if (!Container::instance()->exists(Twilio::class)) {
            Sms::configure(Twilio::class, []);
        }

        return Sms::send($phone, $message, Twilio::class, false);
    }
}
