<?php

namespace Mugonat\Sms\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Mugonat\Sms\Response;
use Mugonat\Sms\Service;
use Mugonat\Sms\Traits\HasConfig;

/**
 *
 */
class Infobip extends Service
{
    use HasConfig;

    public static string $alias = 'infobip';

    protected ?string $host;
    protected ?string $endpoint;
    protected ?string $apiKey;
    protected ?string $senderName;

    /**
     * @param string $phone
     * @param string $message
     * @param callable|null $modifyClientUsing
     * @return Response
     * @throws GuzzleException
     */
    public function send(string $phone, string $message, ?callable $modifyClientUsing = null): Response
    {
        $phone = str_replace('+', '', $phone);
        $url = $this->host.$this->endpoint;

        try {
            $client = new Client(['headers' => [
                'Authorization' => 'App '.$this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]]);

            if (is_callable($modifyClientUsing)) {
                $modifyClientUsing($client);
            }

            $response = $client->post($url, [
                'body' => json_encode([
                    'messages' => [
                        [
                            'from' => $this->senderName,
                            'destinations' => [
                                [
                                    'to' => $phone,
                                ],
                            ],
                            'text' => $message,
                        ],
                    ],
                ], JSON_THROW_ON_ERROR)
            ]);

            return new Response($response->getStatusCode() === 200, $response->getBody());

        } catch (\Exception $e) {
            return new Response(false, null, error: $e->getMessage());
        }
    }

    public function configure(array $config): static
    {
        $this->host = $config['host'] ?? null;
        $this->endpoint = $config['endpoint'] ?? '/sms/2/text/advanced';
        $this->senderName = $config['senderName'] ?? null;
        $this->apiKey = $config['apiKey'] ?? null;

        $this->isConfigured(
            $this->apiKey && $this->host && $this->senderName
        );

        return $this;
    }
}