<?php

namespace App;

use RuntimeException;

class Decoder
{
    public function decode(string $data): string
    {
        $decoded = '';

        $stream = fopen($data, 'rb');

        if (! $headerSize = (int) fgets($stream)) {
            throw new RuntimeException("Unable to decode file. File may be corrupted");
        }

        $tree = $this->extractHeader($stream, $headerSize);

        $content = '';

        while (! feof($stream)) {
            $content .= fread($stream, 1024);
        }

        $padding = ord($content[0]); // Taking first byte as padding

        $data = substr($content, 1);

        $binary = $this->convertToBinary($data);

        if ($padding > 0) {
            $binary = substr($binary, 0, -1 * (8 - $padding)); // Remove padding
        }

        $buffer = '';

        foreach (str_split($binary) as $bit) {
            $buffer .= $bit;

            if (isset($tree[$buffer])) {
                $decoded .= $tree[$buffer];
                $buffer = '';
            }
        }

        fclose($stream);

        return $decoded;
    }

    private function convertToBinary(string $data): string
    {
        return array_reduce(str_split($data), function ($carry, $byte) {
            return $carry.str_pad(decbin(ord($byte)), 8, '0', STR_PAD_LEFT);
        }, '');
    }

    private function extractHeader(mixed $stream, int $size): array
    {
        $chunk = fread($stream, $size);

        return array_flip(json_decode($chunk, true));
    }
}