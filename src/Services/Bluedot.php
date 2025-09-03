<?php

namespace Mugonat\Sms\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Mugonat\Sms\Response;
use Mugonat\Sms\Service;
use Mugonat\Sms\Traits\HasConfig;

/**
 * Represents the Bluedot service for sending SMS messages via the Bluedot API.
 * Implements the Service interface.
 */
class Bluedot extends Service
{
    use HasConfig;

    public static string $alias = 'bluedot';

    protected ?string $apiPassword;
    protected ?string $apiId;
    protected ?string $senderId;

    protected ?string $api;
    private string $type;
    private string $encoding;

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
        $data = json_encode([
            'api_id' => $this->apiId,
            'api_password' => $this->apiPassword,
            'sender_id' => $this->senderId,
            'sms_type' => $this->type,
            'encoding' => $this->encoding,
            'phonenumber' => $phone,
            'textmessage' => $message,
        ], JSON_THROW_ON_ERROR);

        $client = new Client(['headers' => [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]]);

        if (is_callable($modifyClientUsing)) {
            $modifyClientUsing($client);
        }

        $response = $client->post($this->api, ['body' => $data]);
        $content = $response->getBody()->getContents();
        $obj = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        $success = isset($obj['status']) && $obj['status'] === 'S';

        if (!$success) {
            return new Response(
                false,
                $obj,
                $obj['remarks']
            );
        }

        return new Response(true, $obj);
    }

    public function configure(array $config): static
    {
        $this->apiId = $config['api_id'] ?? $config['id'] ?? null;
        $this->apiPassword = $config['api_password'] ?? $config['password'] ?? null;
        $this->senderId = $config['sender_id'] ?? null;

        $this->isConfigured(
            $this->apiId && $this->apiPassword && $this->senderId
        );

        $this->api = $config['api'] ?? 'https://rest.bluedotsms.com/api/SendSMS';
        $this->type = $config['type'] ?? 'T';
        $this->encoding = $config['encoding'] ?? 'T';

        return $this;
    }
}