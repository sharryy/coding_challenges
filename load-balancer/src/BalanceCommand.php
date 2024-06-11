<?php

namespace App;

use Pest\Support\Str;
use React\Http\Browser;
use React\EventLoop\Loop;
use React\Http\HttpServer;
use React\Socket\SocketServer;
use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'balance', description: 'Load Balancer Command')]
class BalanceCommand extends Command
{
    /** @var array<Server> $servers */
    protected array $servers = [];

    protected array $supported = [
        "http://localhost:5001",
        "http://localhost:5002",
        "http://localhost:5003",
        "http://localhost:5004",
        "http://localhost:5005",
    ];

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->servers = array_map(fn($url) => Server::create($url), $this->supported);

        $server = new HttpServer(function (ServerRequestInterface $request) use ($output, $input) {
            $this->writeRequestDetails($output, $request);
            $server = Strategy::fromString($input->getOption('strategy'))->getServer($this->servers);
            $output->writeln("<info>Forwarding request to {$server->getIp()}:{$server->getPort()}</info>");

            return (new Browser())
                ->request($request->getMethod(), "http://{$server->getIp()}:{$server->getPort()}{$request->getUri()->getPath()}")
                ->then(function ($response) use ($output, $server) {
                    $this->writeResponseDetails($output, $server, $response);
                    $server->incrementConnections();
                    return new Response($response->getStatusCode(), $response->getHeaders(), $response->getBody());
                });
        });

        $socket = new SocketServer("127.0.0.1:9001");

        $server->listen($socket);

        Loop::run();

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->addOption('strategy', 's', InputOption::VALUE_REQUIRED, 'Load balancing strategy', 'round-robin');
    }

    private function writeRequestDetails(OutputInterface $output, ServerRequestInterface $request): void
    {
        $output->writeln("Received request from {$request->getUri()->getHost()}");
        $output->writeln("{$request->getMethod()} {$request->getUri()->getPath()} HTTP/{$request->getProtocolVersion()}");
        $output->writeln("Host: {$request->getHeaderLine('Host')}");
        $output->writeln("User-Agent: {$request->getHeaderLine('User-Agent')}");
        $output->writeln("Accept: {$request->getHeaderLine('Accept')}");
    }

    private function writeResponseDetails(OutputInterface $output, Server $server, $response): void
    {
        $output->writeln("Response from {$server->getIp()}:{$server->getPort()}");
        $output->writeln("HTTP/{$response->getProtocolVersion()} {$response->getStatusCode()} {$response->getReasonPhrase()}");
        $output->writeln("Content-Length: {$response->getHeaderLine('Content-Length')}");
        $output->writeln("Content-Type: {$response->getHeaderLine('Content-Type')}");
        $output->writeln($response->getBody());
    }
}