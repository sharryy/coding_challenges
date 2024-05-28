<?php

namespace App;

use Stringable;

class Token implements Stringable
{
    public function __construct(public NodeType $type, public string $value)
    {
    }

    public function __toString(): string
    {
        return sprintf('Token(%s, %s)', $this->type->name, $this->value);
    }
}