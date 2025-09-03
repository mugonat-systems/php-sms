<?php

namespace Mugonat\Sms\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Mugonat\Sms\Response;
use Mugonat\Sms\Service;
use Mugonat\Sms\Traits\HasConfig;

/**
 * Represents the Teleoss service for sending SMS messages via the TeleOSS API.
 * Implements the Service interface.
 */
class Teleoss extends Service
{
    use HasConfig;

    public static string $alias = 'teleoss';

    protected ?string $apiKey;
    protected ?string $senderId;
    protected ?string $api;
    protected ?string $unicode;
    protected ?string $domain;

    /**
     * @param string $phone
     * @param string $message
     * @param callable|null $modifyClientUsing
     * @return Response
     * @throws GuzzleException
     * @throws JsonException
     */
    public function send(string $phone, string $message, ?callable $modifyClientUsing = null): Response
    {
        $phone = str_replace('+', '', $phone);
        $client = new Client();

        if (is_callable($modifyClientUsing)) {
            $modifyClientUsing($client);
        }

        $response = $client->get($this->api, [
            'query' => [
                'apikey' => $this->apiKey,
                'senderid' => $this->senderId,
                'mobiles' => $phone,
                'sms' => $message,
                'unicode' => $this->unicode,
            ]
        ]);

        $content = $response->getBody()->getContents();
        $obj = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        $success = isset($obj['status']['error-code']) && $obj['status']['error-code'] === '000';

        if (!$success) {
            return new Response(
                false,
                $obj,
                $obj['status']['error-description'] ?? 'SMS sending failed'
            );
        }

        return new Response(true, $obj);
    }

    public function configure(array $config): static
    {
        $this->apiKey = $config['api_key'] ?? null;
        $this->senderId = $config['sender_id'] ?? null;
        $this->domain = $config['domain'] ?? null;
        $this->unicode = $config['unicode'] ?? 'no';
        $this->api = $config['api'] ?? null;

        if ($this->domain) {
            $this->api = 'https://' . $this->domain . '/client/api/sendmessage';
        }

        $this->isConfigured(
            $this->apiKey && $this->senderId && $this->api
        );

        return $this;
    }
}
