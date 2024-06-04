<?php

use App\CutCommand;

test('it can read values from tsv file', function () {
    $command = new CutCommand();

    $result = $command->readFields('tests/datasets/sample.tsv', 2);

    expect($result)->toBe([
        'f1', '1', '6', '11', '16', '21'
    ]);
});
