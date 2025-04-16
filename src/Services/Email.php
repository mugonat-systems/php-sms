<?php

namespace Mugonat\Sms\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use Mugonat\Sms\Response;
use Mugonat\Sms\Service;
use Mugonat\Sms\Traits\HasConfig;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * Class File
 *
 * Implements the Service interface. Manages file-based operations, specifically
 * to log messages for SMS functionality.
 *
 * Contains behavior for directory management, filename formatting, and date-based
 * logs for SMS messages.
 */
class Email implements Service
{
    use HasConfig;

    public static string $alias = 'email';

    protected ?string $host;
    protected ?string $username;
    protected ?string $password;
    protected ?string $encryption;
    protected ?int $port;

    public function __construct(array $config = [])
    {
        $this->configure($config);
    }

    public function send(string $phone, string $message): Response
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = $this->host;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $this->username;                     //SMTP username
            $mail->Password   = $this->password;                               //SMTP password
            $mail->SMTPSecure = $this->encryption;            //Enable implicit TLS encryption
            $mail->Port       = $this->port;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            // Recipients
            $mail->addAddress($phone);

            //Content
            $mail->Subject = '[SMS]';
            $mail->Body    = $message;

            $mail->send();

            return new Response(true, $mail);

        } catch (Exception $e) {
            return new Response(false, null, error: $mail->ErrorInfo);
        }
    }

    public function zeroConfig(): bool
    {
        return false;
    }

    public function configure(array $config): static
    {
        $this->host = $config['host'] ?? throw new InvalidArgumentException("Host is required");
        $this->username = $config['username'] ?? null;
        $this->password = $config['password'] ?? null;
        $this->encryption = match ($config['encryption'] ?? '') {
            'ssl' => PHPMailer::ENCRYPTION_SMTPS,
            'tls' => PHPMailer::ENCRYPTION_STARTTLS,
            default => null,
        };
        $this->port = $config['port'] ?? null;

        $this->isConfigured(true);

        return $this;
    }
}