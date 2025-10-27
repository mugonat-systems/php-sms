<?php

namespace Mugonat\Sms\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Mugonat\Sms\Response;
use Mugonat\Sms\Service;
use Mugonat\Sms\Traits\HasConfig;

/**
 * Represents the Mugonat service for sending SMS messages via the Mugonat API.
 * Implements the Service interface.
 */
class Mugonat extends Service
{
    use HasConfig;

    public static string $alias = 'mugonat';

    protected ?string $apiKey;
    protected ?string $apiId;
    protected ?string $senderId;

    protected ?string $api;

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
        
        $client = new Client(['headers' => [
            'X-API-ID' => $this->apiId,
            'X-API-KEY' => $this->apiKey,
        ]]);

        if (is_callable($modifyClientUsing)) {
            $modifyClientUsing($client);
        }

        $url = $this->api . '?' . http_build_query([
            'message' => $message,
            'sender_id' => $this->senderId,
            'to' => $phone,
        ]);

        $response = $client->get($url);
        $content = $response->getBody()->getContents();
        $obj = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        // Adjust success condition based on Mugonat's API response structure
        // You may need to modify this based on the actual response format
        $success = isset($obj['status']) && $obj['status'] === 'success';

        if (!$success) {
            return new Response(
                false,
                $obj,
                $obj['message'] ?? $obj['error'] ?? 'Unknown error'
            );
        }

        return new Response(true, $obj);
    }

    public function configure(array $config): static
    {
        $this->apiId = $config['api_id'] ?? $config['id'] ?? null;
        $this->apiKey = $config['api_key'] ?? $config['key'] ?? null;
        $this->senderId = $config['sender_id'] ?? null;

        $this->isConfigured(
            $this->apiId && $this->apiKey && $this->senderId
        );

        $this->api = $config['api'] ?? 'https://sms.mugonat.com/api/v1/sendMessage';

        return $this;
    }
}