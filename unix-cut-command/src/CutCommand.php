<?php

namespace App;

use SplFileObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'cut', description: 'Cut out selected portions of each line of a file')]
class CutCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (! file_exists($path = $input->getArgument('file'))) {
            $output->writeln('<error>File not found</error>');
            return Command::FAILURE;
        }

        $field = (int) $input->getOption('fields') ?? 1;

        $delimiter = $input->getOption('delimiter') ?? "\t";

        $result = $this->readFields($path, $field, $delimiter);

        $output->writeln(implode("\n", $result));

        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->addOption('fields', 'f', InputOption::VALUE_OPTIONAL, 'The fields to cut')
            ->addOption('delimiter', 'd', InputOption::VALUE_OPTIONAL, 'The delimiter to use', "\t")
            ->addArgument('file', InputArgument::REQUIRED, 'The file to read');
    }

    public function readFields(string $path, int $field, string $delimiter = "\t"): array
    {
        $result = [];

        $file = new SplFileObject($path);

        while (! $file->eof()) {
            $result[] = $file->fgetcsv($delimiter);
        }

        return array_column($result, $field - 1);
    }
}