<?php

namespace App;

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

    public function getConnections(): int
    {
        return $this->connections;
    }

    public function incrementConnections(): void
    {
        $this->connections++;
    }
}