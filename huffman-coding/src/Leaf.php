<?php

namespace App;

class Leaf implements Node
{
    public function __construct(private readonly string $character, private readonly int $weight)
    {

    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function getCharacter(): string
    {
        return $this->character;
    }

    public function getType(): NodeType
    {
        return NodeType::LEAF;
    }

    public function __toString(): string
    {
        return sprintf('Leaf(%s, %d)', $this->character, $this->weight);
    }
}