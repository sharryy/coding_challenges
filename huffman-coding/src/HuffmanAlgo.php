<?php

namespace App;

class HuffmanAlgo
{
    public function encode(string $path, string $output): void
    {
        $encoder = new Encoder;

        $input = $encoder->readFile($path);
        $table = $encoder->countFrequency($input);
        $tree = $encoder->buildTree($table);
        $codes = $encoder->extractCodes($tree);
        $data = $encoder->compress($input, $codes);
        $header = json_encode($codes);

        $stream = fopen($output, 'wb');
        fwrite($stream, mb_strlen($header)."\n");
        fwrite($stream, $header);
        fwrite($stream, $data);
        fclose($stream);
    }

    public function decode(string $path, string $output): string
    {
        $decoder = new Decoder;

        $data = $decoder->decode($path);

        $stream = fopen($output, 'wb');

        fwrite($stream, $data);

        fclose($stream);

        return $output;
    }
}