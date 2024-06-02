<?php

namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'huffman-algo', description: 'Compress a file using Huffman coding')]
class CompressCommand extends Command
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if (! file_exists($input->getArgument('input'))) {
            $output->writeln('<error>Unable to locate file</error>');
            return Command::FAILURE;
        }

        $coder = new HuffmanAlgo();

        if ($input->getOption('encode') && ! $input->getOption('decode')) {
            $coder->encode($input->getArgument('input'), $input->getArgument('output'));
            $sourceSize = filesize($input->getArgument('input'));
            $compressedSize = filesize($input->getArgument('output'));
            $output->writeln('File compressed successfully');
            $output->writeln(sprintf("Source size: %s bytes", number_format($sourceSize)));
            $output->writeln(sprintf("Compressed size: %s bytes", number_format($compressedSize)));
            $output->writeln(sprintf("Size reduced by %s bytes", number_format($sourceSize - $compressedSize)));
        }

        if ($input->getOption('decode')) {
            $coder->decode($input->getArgument('input'), $input->getArgument('output'));
            $output->writeln('File decompressed successfully');
            $output->writeln(sprintf("Decompressed file saved as %s", $input->getArgument('output')));
        }

        return Command::SUCCESS;
    }

    public function configure(): void
    {
        $this
            ->addArgument('input', InputArgument::REQUIRED, 'The input file to compress')
            ->addArgument('output', InputArgument::REQUIRED, 'The output file to write the compressed data to')
            ->addOption('encode', 'e', InputOption::VALUE_NEGATABLE, 'Encode the file using Huffman coding', true)
            ->addOption('decode', 'd', InputOption::VALUE_NEGATABLE, 'Decode the file using Huffman coding', false);
    }
}