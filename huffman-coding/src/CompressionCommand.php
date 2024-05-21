<?php

namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'compress', description: 'Compress a file using Huffman coding')]
class CompressionCommand extends Command
{
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (! file_exists($input->getArgument('file'))) {
            $output->writeln('<error>Unable to locate file</error>');
            return Command::FAILURE;
        }
    }

    public function configure(): void
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'The file to compress');
    }
}