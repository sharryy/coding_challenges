<?php

namespace App;

class HuffmanCoder
{
    /**
     * @param  string  $input
     * @return array
     *
     * Count the frequency of all characters in a given string
     */
    public function buildFrequencyTable(string $input): array
    {
        return array_count_values(mb_str_split($input));
    }
}