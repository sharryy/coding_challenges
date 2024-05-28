<?php

namespace App;

enum NodeType: string
{
    case LEAF = 'leaf';

    case INTERNAL = 'internal';

    public function isLeaf(): bool
    {
        return $this === self::LEAF;
    }

    public function isInternal(): bool
    {
        return $this === self::INTERNAL;
    }
}