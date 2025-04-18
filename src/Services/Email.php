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
 * Class Email
 *
 * Implements the Service interface to send SMS messages via email gateway.
 * This class uses PHPMailer to send emails that act as SMS messages when sent
 * to phone number email addresses (e.g., 1234567890@carrierdomain.com).
 *
 * Requires SMTP configuration including host, authentication credentials,
 * encryption type, and port number.
 */
class Email extends Service
{
    use HasConfig;

    /**
     * The service alias that can be used to reference this service
     */
    public static string $alias = 'email';

    /**
     * SMTP host server
     */
    protected ?string $host;

    /**
     * SMTP username for authentication
     */
    protected ?string $username;

    /**
     * SMTP password for authentication
     */
    protected ?string $password;

    /**
     * Encryption method (ssl/tls)
     */
    protected ?string $encryption;

    /**
     * SMTP port number
     */
    protected ?int $port;

    protected ?string $defaultDomain;
    protected ?string $from;
    protected ?string $fromName;
    protected ?string $subject;

    /**
     * Send an SMS message via email
     *
     * @param string $phone The phone number to send to (will be used as email address)
     * @param string $message The SMS message content
     * @return Response Returns a response object indicating success/failure
     */
    public function send(string $phone, string $message): Response
    {
        $validity = $this->isValidEmail($phone);
        $email = $validity['valid'] ? $phone : "$phone@$this->defaultDomain";
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Disable debug output in production
            $mail->isSMTP();                                         // Use SMTP for sending
            $mail->Host = $this->host;                         // SMTP server address
            $mail->SMTPAuth = !empty($this->username);                                // Enable SMTP authentication
            $mail->Username = $this->username;                     // SMTP username
            $mail->Password = $this->password;                     // SMTP password
            $mail->SMTPSecure = $this->encryption;                   // Encryption method
            $mail->Port = $this->port;                         // SMTP port number

            if (!empty($this->from)) {
                $mail->setFrom($this->from, $this->fromName);
            }

            // Recipients
            $mail->addAddress($email);                               // Phone number as email recipient

            // Email content
            $mail->Subject = $this->subject ?? "SMS for $phone";                                // Fixed subject for SMS emails
            $mail->Body = $message;                              // The actual SMS message

            $mail->send();

            return new Response(true, $mail);

        } catch (Exception $e) {
            return new Response(false, null, error: $mail->ErrorInfo);
        }
    }

    /**
     * Indicates whether this service can work with zero configuration
     *
     * @return bool Always false as this service requires SMTP configuration
     */
    public function zeroConfig(): bool
    {
        return false;
    }

    /**
     * Configure the email service with SMTP settings
     *
     * @param array $config Configuration array containing:
     *                      - host: SMTP server hostname (required)
     *                      - username: SMTP username
     *                      - password: SMTP password
     *                      - encryption: 'ssl' or 'tls'
     *                      - port: SMTP port number
     * @return static
     * @throws InvalidArgumentException if required host is missing
     */
    public function configure(array $config): static
    {
        $this->host = $config['host'] ?? 'localhost';
        $this->username = $config['username'] ?? null;
        $this->password = $config['password'] ?? null;
        $this->encryption = match ($config['encryption'] ?? '') {
            'ssl' => PHPMailer::ENCRYPTION_SMTPS,
            'tls' => PHPMailer::ENCRYPTION_STARTTLS,
            default => null,
        };
        $this->port = $config['port'] ?? 1025;
        $this->defaultDomain = $config['domain'] ??  'app.test';
        $this->from = $config['from'] ?? null;
        $this->fromName = $config['fromName'] ?? null;
        $this->subject = $config['subject'] ?? null;

        $this->isConfigured(true);

        return $this;
    }

    /**
     * Validates an email address according to RFC standards
     *
     * @param string $email The email address to validate
     * @param bool $checkDNS Whether to verify the domain has valid MX records (default: false)
     * @return array{valid: bool, reason: string, domain: string} Returns an associative array with:
     *              - 'Valid' (bool): Whether the email is valid
     *              - 'reason' (string|null): Failure reason if invalid
     *              - 'domain' (string|null): Extracted domain if valid format
     */
    private function isValidEmail(string $email, bool $checkDNS = false): array
    {
        // Basic format validation
        if (empty($email)) {
            return [
                'valid' => false,
                'reason' => 'Email cannot be empty',
                'domain' => null
            ];
        }

        // Validate email format using filter_var
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'valid' => false,
                'reason' => 'Invalid email format',
                'domain' => null
            ];
        }

        // Extract domain part
        $domain = substr($email, strrpos($email, '@') + 1);

        // Optional DNS check for MX records
        if ($checkDNS) {
            if (!checkdnsrr($domain, 'MX')) {
                return [
                    'valid' => false,
                    'reason' => 'Domain does not exist or has no MX records',
                    'domain' => $domain
                ];
            }
        }

        return [
            'valid' => true,
            'reason' => null,
            'domain' => $domain
        ];
    }
}