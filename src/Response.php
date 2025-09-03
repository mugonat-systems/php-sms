<?php

namespace Mugonat\Sms;

class Response
{
    public function __construct(
        public bool $isSuccessful,
        public $response,
        public ?string $error = null,
    ){}

    public function hasError(): bool
    {
        return !empty($this->error);
    }
}