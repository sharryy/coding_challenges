<?php

test('it parses the field commands', function ($flag, $output) {
    exec("php artisan cut $flag tests/datasets/sample.tsv", $response);
    expect($response)->toBe($output);
})->with([
    ['-f1', ['f0', "0", "5", "10", "15", "20"]],
    ['-f2', ['f1', "1", "6", "11", "16", "21"]],
    ['-f3', ['f2', "2", "7", "12", "17", "22"]],
    ['-f4', ['f3', "3", "8", "13", "18", "23"]],
    ['-f5', ['f4', "4", "9", "14", "19", "24"]],
    ['-f1,3', ["f0\tf2", "0\t2", "5\t7", "10\t12", "15\t17", "20\t22"]],
    ['-f"1 3"', ["f0\tf2", "0\t2", "5\t7", "10\t12", "15\t17", "20\t22"]],
    ['-f1,3,5', ["f0\tf2\tf4", "0\t2\t4", "5\t7\t9", "10\t12\t14", "15\t17\t19", "20\t22\t24"]],
    ['-f"1 3 5"', ["f0\tf2\tf4", "0\t2\t4", "5\t7\t9", "10\t12\t14", "15\t17\t19", "20\t22\t24"]],
    [
        '-f1,2,3,4,5',
        [
            "f0\tf1\tf2\tf3\tf4",
            "0\t1\t2\t3\t4",
            "5\t6\t7\t8\t9",
            "10\t11\t12\t13\t14",
            "15\t16\t17\t18\t19",
            "20\t21\t22\t23\t24"
        ]
    ],
    [
        '-f"1 2 3 4 5"',
        [
            "f0\tf1\tf2\tf3\tf4",
            "0\t1\t2\t3\t4",
            "5\t6\t7\t8\t9",
            "10\t11\t12\t13\t14",
            "15\t16\t17\t18\t19",
            "20\t21\t22\t23\t24"
        ]
    ],
]);

test('it parses field commands with delimiters', function ($flag, $ouput) {
    exec("php artisan cut $flag tests/datasets/fourchords.csv | head -n5", $response);
    expect($response)->toBe($ouput);
})->with([
    [
        '-f1 -d,',
        [
            "Song title",
            "10000 Reasons (Bless the Lord)",
            "20 Good Reasons",
            "Adore You",
            "Africa"
        ]
    ],
    [
        '-f1,2 -d,',
        [
            "Song title,Artist",
            "10000 Reasons (Bless the Lord),Matt Redman\u{A0}and\u{A0}Jonas Myrin",
            "20 Good Reasons,Thirsty Merc",
            "Adore You,Harry Styles",
            "Africa,Toto"
        ]
    ],
    [
        '-f"1 2" -d,',
        [
            "Song title,Artist",
            "10000 Reasons (Bless the Lord),Matt Redman\u{A0}and\u{A0}Jonas Myrin",
            "20 Good Reasons,Thirsty Merc",
            "Adore You,Harry Styles",
            "Africa,Toto"
        ]
    ]
]);

it('reads input from stdin if no file given', function () {
    $input = "1\t2\t3\t4\t5\n6\t7\t8\t9\t10\n11\t12\t13\t14\t15\n16\t17\t18\t19\t20\n21\t22\t23\t24\t25";
    $output = "1\t2\n6\t7\n11\t12\n16\t17\n21\t22\n";
    $response = shell_exec("echo '$input' | php artisan cut -f1,2");
    expect($response)->toBe($output);
});