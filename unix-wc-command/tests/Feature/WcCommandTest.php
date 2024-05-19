<?php

it('should return the number of lines, words, characters and bytes in a file', function () {
    $output = shell_exec('php artisan wc -m tests/datasets/test.txt');

    expect($output)->toBe("7145\t58065\t342190\t339292\ttests/datasets/test.txt\n");
});

it('should accept file from STDIN', function () {
    $output = shell_exec('echo "Hello World" | php artisan wc -m');

    expect($output)->toBe("1\t2\t12\t12\t\n");
});