<?php

use App\HuffmanCoder;

it('can count the frequency of characters', function () {
    $file = file_get_contents('tests/datasets/step1/test.txt');

    $coder = new HuffmanCoder();

    $result = $coder->buildFrequencyTable($file);

    expect($result)->toBe([
        'L' => 1,
        'o' => 3,
        'r' => 2,
        'e' => 2,
        'm' => 3,
        ' ' => 4,
        'i' => 2,
        'p' => 1,
        's' => 2,
        'u' => 1,
        'd' => 1,
        'l' => 1,
        't' => 2,
        'a' => 1,
    ]);
});

it('can count the frequency of numbers and characters', function () {
    $file = file_get_contents('tests/datasets/step1/test1.txt');

    $coder = new HuffmanCoder();

    $result = $coder->buildFrequencyTable($file);

    expect($result)->toBe([
        'T' => 1,
        'h' => 5,
        3 => 9,
        'r' => 4,
        ' ' => 13,
        5 => 6,
        'a' => 3,
        'n' => 5,
        0 => 1,
        'l' => 1,
        'd' => 1,
        'y' => 2,
        1 => 6,
        'g' => 1,
        7 => 9,
        4 => 1,
        '"' => 2,
        'f' => 1,
        "'" => 2,
        '.' => 1,
    ]);
});