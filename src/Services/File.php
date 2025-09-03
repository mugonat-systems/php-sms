<?php

namespace Mugonat\Sms\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Mugonat\Sms\Response;
use Mugonat\Sms\Service;
use Mugonat\Sms\Traits\HasConfig;
use RuntimeException;

/**
 * Class File
 *
 * Implements the Service interface. Manages file-based operations, specifically
 * to log messages for SMS functionality.
 *
 * Contains behavior for directory management, filename formatting, and date-based
 * logs for SMS messages.
 */
class File extends Service
{
    use HasConfig;

    public static string $alias = 'file';

    protected ?string $directory;
    protected ?string $fileNameFormat;

    protected ?string $fileDateFormat;

    public function send(string $phone, string $message, ?callable $modifyClientUsing = null): Response
    {
        if ((dir($this->directory) === false) && !mkdir($concurrentDirectory = $this->directory) && !is_dir($concurrentDirectory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        $fileName = implode(DIRECTORY_SEPARATOR, [
            $this->directory,
            str_replace('{date}', date($this->fileDateFormat), $this->fileNameFormat)
        ]);

        file_put_contents($fileName, date('H:i:s') . ": $phone - $message" . PHP_EOL, FILE_APPEND);

        return new Response(true, $fileName);
    }

    public function zeroConfig(): bool
    {
        return true;
    }

    public function configure(array $config): static
    {
        $this->directory = $config['directory'] ?? __DIR__;
        $this->fileNameFormat = $config['file_name_format'] ?? 'sms-{date}.log';
        $this->fileDateFormat = $config['file_date_format'] ?? 'Y-m-d';

        return $this;
    }
}