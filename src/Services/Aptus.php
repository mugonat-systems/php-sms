<?php

namespace Mugonat\Sms\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Mugonat\Sms\Response;
use Mugonat\Sms\Service;
use Mugonat\Sms\Traits\HasConfig;

/**
 * Represents the Aptus service for sending SMS messages via the Aptus API.
 * Implements the Service interface.
 */
class Aptus implements Service
{
    use HasConfig;

    public static string $alias = 'aptus';

    protected ?string $username;
    protected ?string $password;
    protected ?string $senderId;

    public function __construct(array $config = [])
    {
        $this->configure($config);
    }

    public function send(string $phone, string $message): Response
    {
        $phone = str_replace('+', '', $phone);
        $message = urlencode($message);

        try {
            $client = new Client();
            $response = $client->get(
                "https://www.sms.co.tz/api.php?do=sms&username={$this->username}&password={$this->password}&senderid={$this->senderId}&msg=$message&dest=$phone"
            );

            $responseBody = $response->getBody()->getContents();

//            // Parse the Aptus API response (replace with actual Aptus response parsing logic)
//            $success = true; // Assuming success based on response parsing (implement proper logic)
//            $data = [
//                'status' => $success ? 'success' : 'failed',
//                // Add other relevant data from the Aptus response
//            ];

            return new Response($response->getStatusCode() == 200, $responseBody);

        } catch (GuzzleException $e) {
            return new Response(false, [], $e->getMessage());
        }
    }

    public function configure(array $config): static
    {
        $this->username = $config['username'] ?? null;
        $this->password = $config['password'] ?? null;
        $this->senderId = $config['sender_id'] ?? null;

        $this->isConfigured(
            $this->username && $this->password && $this->senderId
        );

        return $this;
    }
}