<?php

namespace Mugonat\Sms\Traits;

/**
 * A trait that provides configuration checking functionality.
 */
trait HasConfig
{
    protected bool $configured = false;

    public function isConfigured(?bool $value = null): bool
    {
        if($value !== null){
            $this->configured = $value;
        }

        return $this->configured || $this->zeroConfig();
    }

    public function zeroConfig(): bool
    {
        return false;
    }
}