<?php

namespace App;

enum NodeType: string
{
    case LEAF = 'leaf';

    case INTERNAL = 'internal';
}