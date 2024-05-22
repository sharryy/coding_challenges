<?php

namespace App;

class Internal implements Node
{
    public function __construct(private readonly Node $left, private readonly Node $right, private readonly int $weight)
    {
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function getLeft(): Node
    {
        return $this->left;
    }

    public function getRight(): Node
    {
        return $this->right;
    }

    public function getType(): NodeType
    {
        return NodeType::INTERNAL;
    }

    public function __toString(): string
    {
        return sprintf('Internal(%s, %s, %d)', $this->left, $this->right, $this->weight);
    }
}