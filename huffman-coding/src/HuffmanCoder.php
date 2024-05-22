<?php

namespace App;

use SplPriorityQueue;

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

    /**
     * This methods build the huffman-tree and returns the top element
     *
     * @param  array  $frequency
     * @return Node
     */
    public function buildHuffmanTree(array $frequency): Node
    {
        // "Huffman-tree" is built using bottom-to-top approach. Means we will first
        // add leaf nodes to the tree and then internal nodes.
        $queue = new SplPriorityQueue();

        foreach ($frequency as $char => $weight) {
            // By default, Priority queues in php are based on MaxHeap
            // Since we want to sort the queue based on the weight of the nodes
            // in ascending order, we need this dirty-hack to negate the weight of
            // the nodes to make sure that it acts like "min-heap"
            $queue->insert(new Leaf($char, $weight), -$weight);
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

            // Again negative weight to make sure that it acts like "min-heap"
            $queue->insert(new Internal($first, $second, $weight), -($weight));

            if ($queue->isCorrupted()) {
                $queue->recoverFromCorruption();
            }
        }

        return $queue->top();
    }
}