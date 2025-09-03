<?php

namespace Mugonat\Sms\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Mugonat\Sms\Response;
use Mugonat\Sms\Service;
use Mugonat\Sms\Traits\HasConfig;

class MessageBird extends Service
{
    use HasConfig;

    public static string $alias = 'message-bird';

    protected ?string $accessKey;
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
            'base_uri' => 'https://rest.messagebird.com/messages',
            'headers' => [
                'Authorization' => 'AccessKey ' . $this->accessKey,
            ],
        ]);

        if (is_callable($modifyClientUsing)) {
            $modifyClientUsing($client);
        }

        try {
            $response = $client->post('', [
                'json' => [
                    'originator' => $this->fromNumber,
                    'recipients' => [$phone],
                    'body' => $message,
                ],
            ]);

            $responseBody = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

            $success = !empty($responseBody['id']);
            $data = $responseBody;

            return new Response($success, $data);

        } catch (GuzzleException|JsonException $e) {
            return new Response(false, [], $e->getMessage());
        }
    }

    public function configure(array $config): static
    {
        $this->accessKey = $config['access_key'] ?? null;
        $this->fromNumber = $config['from_number'] ?? null;

        $this->isConfigured(
            $this->accessKey && $this->fromNumber
        );

        return $this;
    }
}