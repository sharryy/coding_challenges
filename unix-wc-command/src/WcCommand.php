<?php

namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'wc')]
class WcCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $outputString = "";

        if (! $file = $input->getArgument('file')) {
            $stream = fopen('php://stdin', 'r');
        } else {
            if (! file_exists($file)) {
                return Command::FAILURE;
            }
            $stream = fopen($file, 'r');
        }

        $lines = $bytes = $words = $chars = 0;

        while (($line = fgets($stream)) !== false) {
            $lines++;
            $bytes += strlen($line);
            $words += str_word_count($line);
            $chars += mb_strlen($line);
        }

        if ($input->getOption('lines')) {
            $outputString .= $lines."\t";
        }

        if ($input->getOption('words')) {
            $outputString .= $words."\t";
        }

        if ($input->getOption('bytes')) {
            $outputString .= $bytes."\t";
        }

        if ($input->getOption('chars')) {
            $outputString .= $chars."\t";
        }

        $output->writeln("$outputString$file");

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->addOption('bytes', 'c', InputOption::VALUE_NEGATABLE, 'Print the byte counts', true)
            ->addOption('lines', 'l', InputOption::VALUE_NEGATABLE, 'Print number of lines', true)
            ->addOption('words', 'w', InputOption::VALUE_NEGATABLE, 'Number of words in a file', true)
            ->addOption('chars', 'm', InputOption::VALUE_NEGATABLE, 'Print the character counts', false)
            ->addArgument('file', InputArgument::OPTIONAL, 'File to count the lines, words and characters');
    }
}