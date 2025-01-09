<?php

namespace Mugonat\Sms\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Mugonat\Sms\Response;
use Mugonat\Sms\Services\Infobip;
use PHPUnit\Framework\TestCase;

class InfobipTest extends TestCase
{
    protected Infobip $infobip;

    protected function setUp(): void
    {
        $this->infobip = new Infobip([
            'host' => 'https://api.infobip.com',
            'endpoint' => '/sms/2/text/advanced',
            'apiKey' => 'test_api_key',
            'senderName' => 'TestSender',
        ]);
    }

    public function testSendSuccess(): void
    {
        $mockClient = $this->createMock(Client::class);
        $mockClient->expects($this->once())
            ->method('post')
            ->willReturn(new GuzzleResponse(200, [], '{"messages":[{"status":{"groupId":1, "description":"Message sent successfully"}}]}'));

        $this->infobip = $this->getMockBuilder(Infobip::class)
            ->setConstructorArgs([[
                'host' => 'https://api.infobip.com',
                'endpoint' => '/sms/2/text/advanced',
                'apiKey' => 'test_api_key',
                'senderName' => 'TestSender',
            ]])
            ->onlyMethods(['send'])
            ->getMock();

        $this->infobip->method('send')
            ->will($this->returnCallback(function ($phone, $message) use ($mockClient) {
                $phone = str_replace('+', '', $phone);
                $response = $mockClient->post('https://api.infobip.com/sms/2/text/advanced', [
                    'messages' => [
                        [
                            'from' => 'TestSender',
                            'destinations' => [
                                [
                                    'to' => $phone,
                                ],
                            ],
                            'text' => $message,
                        ],
                    ],
                ]);
                return new Response(true, $response->getBody());
            }));


    }
}