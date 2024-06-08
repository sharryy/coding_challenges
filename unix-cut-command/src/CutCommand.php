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
    const STDIN = "php://stdin";

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (is_null($path = $input->getArgument('file')) || $input->getArgument('file') === '-') {
            $path = self::STDIN; // read from standard input
        }

        $fields = $this->parseFields($input->getOption('fields'));

        $fields = array_map(fn($field) => max(0, $field - 1), $fields);

        $delimiter = $input->getOption('delimiter') ?? "\t";

        $result = $this->readFields($path, $delimiter);

        foreach ($result as $line) {
            $output->writeln(implode($delimiter, array_intersect_key($line, array_flip($fields))));
        }

        return Command::SUCCESS;
    }

    public function readFields(string $path, string $delimiter = "\t"): array
    {
        $result = [];

        if ($path == self::STDIN) {
            $result = file(self::STDIN, FILE_IGNORE_NEW_LINES);
            return array_map(fn($line) => str_getcsv($line, $delimiter), $result);
        }

        $file = new SplFileObject($path);

        $bom = $file->fread(3); // removing byte-order mark (BOM) from file (\xEF\xBB\xBF) - 3 bytes

        if ($bom !== pack('H*', 'EFBBBF')) {
            if ($file->isFile()) {
                $file->rewind();
            }
        }

        while (! $file->eof()) {
            $result[] = $file->fgetcsv($delimiter);
        }

        return $result;
    }

    public function parseFields(?string $fields): array
    {
        if (is_null($fields)) {
            return [];
        }

        if (is_numeric($fields)) {
            return [$fields];
        }

        if (str_contains($fields, ',')) {
            return array_map('trim', explode(',', $fields));
        }

        return preg_split('/\s+/', $fields);
    }

    protected function configure(): void
    {
        $this->addOption('fields', 'f', InputOption::VALUE_OPTIONAL, 'The fields to cut')
            ->addOption('delimiter', 'd', InputOption::VALUE_OPTIONAL, 'The delimiter to use', "\t")
            ->addArgument('file', InputArgument::OPTIONAL, 'The file to read');
    }
}