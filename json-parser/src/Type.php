<?php

namespace App;

enum Type: string
{
    case LEFT_BRACE = '{';
    case RIGHT_BRACE = '}';
    case LEFT_BRACKET = '[';
    case RIGHT_BRACKET = ']';
    case STRING = 'string';
    case NUMBER = 'number';
    case BOOLEAN = 'boolean';
    case NULL = 'null';
    case COMMA = ',';
    case COLON = ':';
}