<?php

use App\HuffmanAlgo;

it('can encode and decode a file', function () {
    $inputPath = 'tests/datasets/test1.txt';

    $encodedPath = 'tests/datasets/encoded.bin';

    $coder = new HuffmanAlgo();

    $coder->encode($inputPath, $encodedPath);

    $decodedPath = $coder->decode($encodedPath, 'tests/datasets/decoded.txt');

    $this->assertSame(file_get_contents($inputPath), file_get_contents($decodedPath));
});