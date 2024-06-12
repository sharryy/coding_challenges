<?php

namespace App;

use InvalidArgumentException;

enum Strategy: string
{
    case ROUND_ROBIN = 'round-robin';
    case RANDOM = 'random';
    case LEAST_CONNECTION = 'least-connection';
    case IP_HASH = 'ip-hash';

    public static function fromString(string $strategy): self
    {
        return match ($strategy) {
            'round-robin' => self::ROUND_ROBIN,
            'random' => self::RANDOM,
            'least-connection' => self::LEAST_CONNECTION,
            'ip-hash' => self::IP_HASH,
            default => throw new InvalidArgumentException("Invalid strategy: $strategy"),
        };
    }

    public function getServer(array $servers, array $params = []): Server
    {
        $servers = $this->getHealthyServers($servers);

        if (empty($servers)) {
            throw new NoHealthyServersFound();
        }

        return match ($this) {
            self::ROUND_ROBIN => $this->roundRobin($servers),
            self::RANDOM => $this->random($servers),
            self::LEAST_CONNECTION => $this->leastConnection($servers),
            self::IP_HASH => $this->ipHash($servers, $params),
        };
    }

    public function toString(): string
    {
        return $this->value;
    }

    private function roundRobin(array $servers): Server
    {
        static $index = 0;

        $server = $servers[$index];

        $index = ($index + 1) % count($servers);

        return $server;
    }

    private function random(array $servers): Server
    {
        return $servers[array_rand($servers)];
    }

    private function leastConnection(array $servers): Server
    {
        usort($servers, fn(Server $a, Server $b) => $a->getConnections() <=> $b->getConnections());

        return $servers[0];
    }

    private function ipHash(array $servers, array $params): Server
    {
        if (! isset($params['REMOTE_ADDR'])) {
            throw new InvalidArgumentException('Missing REMOTE_ADDR in request params');
        }

        $ip = $params['REMOTE_ADDR'];

        $hash = ip2long($ip) % count($servers);

        return $servers[$hash];
    }

    private function getHealthyServers(array $servers): array
    {
        return array_values(array_filter($servers, fn(Server $server) => $server->isHealthy()));
    }
}
