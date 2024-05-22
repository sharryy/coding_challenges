<?php

namespace App;

use Stringable;

interface Node extends Stringable
{
    /**
     * Get the weight of the node
     *
     * @return int
     */
    public function getWeight(): int;

    /**
     * Get The type of the Node.
     *
     * @return NodeType
     */
    public function getType(): NodeType;
}