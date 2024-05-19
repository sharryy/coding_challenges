<?php

namespace App;

use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'json-parser')]
class JsonParserCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (! file_exists($input->getArgument('file'))) {
            $output->writeln('File does not exist.');
            return Command::FAILURE;
        }

        $parser = new JsonParser(new Lexer);

        try {
            $results = $parser->parse(file_get_contents($input->getArgument('file')));
            $output->writeln(json_encode($results, JSON_PRETTY_PRINT));
            return Command::SUCCESS;
        } catch (RuntimeException $e) {
            $output->writeln($e->getMessage());
            return Command::FAILURE;
        }

    }

    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'The JSON file to parse');
    }
}