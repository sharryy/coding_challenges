<?php

namespace App;

use InvalidArgumentException;

class Server
{
    public function __construct(
        protected string $ip,
        protected int $port,
        protected bool $healthy = true,
        protected int $connections = 0
    ) {
    }

    public static function create(string $url): self
    {
        $url = parse_url($url);

        if ($url === false || ! isset($url['host'], $url['port'])) {
            throw new InvalidArgumentException(sprintf('Invalid URL: %s', $url));
        }

        return new self($url['host'], $url['port'], true, 0);
    }

    public function isHealthy(): bool
    {
        return $this->healthy;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function inactivate(): void
    {
        $this->healthy = false;
    }

    public function activate(): void
    {
        $this->healthy = true;
    }

    public function getConnections(): int
    {
        return $this->connections;
    }

    public function incrementConnections(): void
    {
        $this->connections++;
    }
}