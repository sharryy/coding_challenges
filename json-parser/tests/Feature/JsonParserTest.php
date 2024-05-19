<?php

test('step 1 - invalid json', function () {
    exec('php artisan json-parser tests/datasets/step1/invalid.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 1 - valid json', function () {
    exec('php artisan json-parser tests/datasets/step1/valid.json', $output, $return);

    expect($return)->toBe(0);
});

test('step 2 - invalid json', function () {
    exec('php artisan json-parser tests/datasets/step2/invalid.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 2 - invalid2 json', function () {
    exec('php artisan json-parser tests/datasets/step2/invalid2.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 2 - valid json', function () {
    exec('php artisan json-parser tests/datasets/step2/valid.json', $output, $return);

    expect($return)->toBe(0);
});

test('step 2 - valid2 json', function () {
    exec('php artisan json-parser tests/datasets/step2/valid2.json', $output, $return);

    expect($return)->toBe(0);
});

test('step 3 - invalid json', function () {
    exec('php artisan json-parser tests/datasets/step3/invalid.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 3 - valid json', function () {
    exec('php artisan json-parser tests/datasets/step3/valid.json', $output, $return);

    expect($return)->toBe(0);
});

test('step 4 - invalid json', function () {
    exec('php artisan json-parser tests/datasets/step4/invalid.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 4 - valid json', function () {
    exec('php artisan json-parser tests/datasets/step4/valid.json', $output, $return);

    expect($return)->toBe(0);
});

test('step 4 - valid2 json', function () {
    exec('php artisan json-parser tests/datasets/step4/valid2.json', $output, $return);

    expect($return)->toBe(0);
});

test('step 5 - invalid1 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid1.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid2 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid2.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid3 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid3.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid4 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid4.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid5 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid5.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid6 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid6.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid7 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid7.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid8 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid8.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid9 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid9.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid10 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid10.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid11 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid11.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid12 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid12.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid13 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid13.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid14 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid14.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid15 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid15.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid16 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid16.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid17 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid17.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid19 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid19.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid20 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid20.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid21 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid21.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid22 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid22.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid23 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid23.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid24 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid24.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid25 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid25.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid26 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid26.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid27 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid27.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid28 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid28.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid29 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid29.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid30 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid30.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid31 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid31.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid32 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid32.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid33 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid33.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - invalid34 json', function () {
    exec('php artisan json-parser tests/datasets/step5/invalid34.json', $output, $return);

    expect($return)->toBe(1);
});

test('step 5 - valid1 json', function () {
    exec('php artisan json-parser tests/datasets/step5/valid1.json', $output, $return);

    expect($return)->toBe(0);
});

test('step 5 - valid2 json', function () {
    exec('php artisan json-parser tests/datasets/step5/valid2.json', $output, $return);

    expect($return)->toBe(0);
});

test('step 5 - valid3 json', function () {
    exec('php artisan json-parser tests/datasets/step5/valid3.json', $output, $return);

    expect($return)->toBe(0);
});

test('step 5 - valid4 json', function () {
    exec('php artisan json-parser tests/datasets/step5/valid4.json', $output, $return);

    expect($return)->toBe(0);
});

test('step 5 - valid5 json', function () {
    exec('php artisan json-parser tests/datasets/step5/valid5.json', $output, $return);

    expect($return)->toBe(0);
});

test('step 5 - valid6 json', function () {
    exec('php artisan json-parser tests/datasets/step5/valid6.json', $output, $return);

    expect($return)->toBe(0);
});
