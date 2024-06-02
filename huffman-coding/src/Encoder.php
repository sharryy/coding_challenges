<?php

namespace App;

use SplPriorityQueue;
use RuntimeException;

class Encoder
{
    public function countFrequency(string $input): array
    {
        return array_count_values(mb_str_split($input));
    }

    public function buildTree(array $frequency): Node
    {
        // "Huffman-tree" is built using bottom-to-top approach. Means we will first
        // add leaf nodes to the tree and then internal nodes.
        $queue = new SplPriorityQueue();

        foreach ($frequency as $char => $weight) {
            // By default, Priority queues in php are based on MaxHeap
            // Since we want to sort the queue based on the weight of the nodes
            // in ascending order, we need this dirty-hack to negate the weight of
            // the nodes to make sure that it acts like "min-heap"
            $queue->insert(value: new Leaf($char, $weight), priority: -$weight);
        }

        // We will always extract two minimum frequency nodes from the queue, sum their weights
        // and create a parent for them with the weight equal to the sum.
        // For example, from a frequency list of [2, 3, 4, 5]
        // We'll pick 2 and 3, sum them to 5 and create a parent node with weight 5.
        //      4,  5  ,5   (This would be the queue after the first iteration)
        //        2   3
        while ($queue->count() > 1) {
            $first = $queue->extract();
            $second = $queue->extract();
            $weight = $first->getWeight() + $second->getWeight();
            $queue->insert(value: new Internal($first, $second, $weight), priority: -($weight));
        }

        return $queue->top();
    }

    public function extractCodes(Node $node, string $prefix = '', array &$result = []): array
    {
        if ($node->getType()->isLeaf()) {
            /** @var Leaf $node */
            $result[$node->getCharacter()] = $prefix;
        } else {
            // The process is pretty simple
            // - Assign 0 to all the left edges
            // - Assign 1 to all the right edges
            // Build the prefix code while traversing from root to leaf
            /** @var Internal $node */
            $this->extractCodes($node->getLeft(), "{$prefix}0", $result);
            $this->extractCodes($node->getRight(), "{$prefix}1", $result);
        }

        return $result;
    }

    public function compress(string $input, array $codes): string
    {
        // Encode the data using frequency table
        $encoded = array_reduce(mb_str_split($input), fn($carry, $char) => $carry.$codes[$char], '');

        $padding = strlen($encoded) % 8;

        // Make the length of the encoded data a multiple of 8.
        // This would help in decoding.
        if ($padding > 0) {
            $encoded .= str_repeat('0', 8 - $padding);
        }

        $chunks = str_split($encoded, 8);

        return chr($padding).implode('', array_map(fn($chunk) => chr(bindec($chunk)), $chunks));
    }

    public function readFile(string $path): string
    {
        $stream = fopen($path, 'rb');

        if (! $stream) {
            throw new RuntimeException("Unable to read file");
        }

        return stream_get_contents($stream);
    }
}