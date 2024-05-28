<?php

use App\HuffmanCoder;

it('can compress a file', function () {
    $file = file_get_contents('tests/datasets/step1/something.txt');

    $coder = new HuffmanCoder();

    $coder->encode($file, 'tests/datasets/step1/test.bin');
});