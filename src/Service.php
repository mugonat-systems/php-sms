<?php

namespace Mugonat\Sms;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Mugonat\Container\Exceptions\NotFoundException;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;

interface Service
{
    public function send(string $phone, string $message): Response;
}