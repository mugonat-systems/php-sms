<?php

namespace Mugonat\Sms\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Mugonat\Sms\Response;
use Mugonat\Sms\Service;
use Mugonat\Sms\Traits\HasConfig;

/**
 * Represents the Twilio service for sending SMS messages via the Twilio API.
 * Implements the Service interface.
 */
class Twilio extends Service
{
    use HasConfig;

    public static string $alias = 'twilio';

    protected ?string $accountSid;
    protected ?string $authToken;
    protected ?string $fromNumber;

    /**
     * @param string $phone
     * @param string $message
     * @param callable|null $modifyClientUsing
     * @return Response
     */
    public function send(string $phone, string $message, ?callable $modifyClientUsing = null): Response
    {
        $client = new Client([
            'base_uri' => 'https://api.twilio.com/2010-04-01/Accounts/' . $this->accountSid . '/Messages.json',
            'auth' => [$this->accountSid, $this->authToken],
        ]);

        if (is_callable($modifyClientUsing)) {
            $modifyClientUsing($client);
        }

        try {
            $response = $client->post('', [
                'form_params' => [
                    'To' => $phone,
                    'From' => $this->fromNumber,
                    'Body' => $message,
                ],
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true);

            $success = !empty($responseBody['sid']);
            $data = $responseBody;

            return new Response($success, $data);

        } catch (GuzzleException $e) {
            return new Response(false, [], $e->getMessage());
        }
    }

    public function configure(array $config): static
    {
        $this->accountSid = $config['account_sid'] ?? null;
        $this->authToken = $config['auth_token'] ?? null;
        $this->fromNumber = $config['from_number'] ?? null;

        $this->isConfigured(
            $this->accountSid && $this->authToken && $this->fromNumber
        );

        return $this;
    }
}