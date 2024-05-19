<?php

namespace App;

use RuntimeException;

class JsonParser
{
    /**
     * @var array $results
     *
     * The results of the parsing
     */
    protected array $results = [];

    /**
     * @var int $cursor
     *
     * The cursor position in the tokens array to keep track
     */
    protected int $cursor = 0;

    public function __construct(protected Lexer $lexer)
    {
    }

    public function parse(string $json): array
    {
        $tokens = $this->lexer->tokenize($json);

        return $this->validate($tokens);
    }

    private function validate(array $tokens): array
    {
        $length = count($tokens);

        while ($this->cursor < $length) {
            // Only two conditions because JSON can only start from
            // either an object or array. There is no third option.
            if ($tokens[$this->cursor]->type == Type::LEFT_BRACE) {
                $this->move(Type::LEFT_BRACE, $tokens);
                $this->results = $this->object($tokens);
            } elseif ($tokens[$this->cursor]->type == Type::LEFT_BRACKET) {
                $this->move(Type::LEFT_BRACKET, $tokens);
                $this->results = $this->array($tokens);
            } else {
                throw new RuntimeException("Unexpected token: `{$tokens[$this->cursor]->type->value}`");
            }
        }

        return $this->results;
    }

    private function object(array $tokens): array
    {
        $results = [];

        while ($this->peek($tokens)->type !== Type::RIGHT_BRACE) {
            // Valid Object looks like that
            // {"string" : "value" }
            // That's what we are doing
            // First parsing key which should be string, then colon and then value
            $key = $this->string($tokens);
            $this->move(Type::COLON, $tokens);
            $value = $this->value($tokens);

            $results[$key] = $value;

            if ($this->peek($tokens)->type === Type::RIGHT_BRACE) {
                break;
            }

            $this->move(Type::COMMA, $tokens);

            if ($this->peek($tokens)->type === Type::RIGHT_BRACE) {
                throw new RuntimeException("Unexpected comma at the end of object");
            }
        }

        $this->move(Type::RIGHT_BRACE, $tokens);

        return $results;
    }

    private function string(array $tokens): string
    {
        if ($this->peek($tokens)->type !== Type::STRING) {
            throw new RuntimeException("Unexpected token: `{$this->peek($tokens)->type->value}`");
        }

        $key = $this->peek($tokens)->value;
        $this->cursor++;

        return $key;
    }

    private function value(array $tokens): mixed
    {
        $token = $this->peek($tokens);

        $this->cursor++;

        // Values method is a generic method to accommodate values for objects and arrays
        // In JSON, values can be null, string, boolean, number, object or array
        return match ($token->type) {
            Type::NULL => null,
            Type::STRING => $token->value,
            Type::BOOLEAN => $token->value === 'true',
            Type::NUMBER => $this->number($token),
            Type::LEFT_BRACE => $this->object($tokens),
            Type::LEFT_BRACKET => $this->array($tokens),
            default => throw new RuntimeException("Unexpected token: `{$token->type->value}`"),
        };
    }

    private function peek(array $tokens): Token
    {
        if ($this->cursor >= count($tokens)) {
            throw new RuntimeException("Unexpected end of input");
        }

        return $tokens[$this->cursor];
    }

    private function move(Type $type, array $tokens): void
    {
        if ($tokens[$this->cursor]->type !== $type) {
            throw new RuntimeException("Unexpected token: `{$tokens[$this->cursor]->type->value}`");
        }

        $this->cursor++;
    }

    private function array(array $tokens): array
    {
        $results = [];

        while ($this->peek($tokens)->type !== Type::RIGHT_BRACKET) {
            // Since array can't have keys, we are directly parsing values.
            // Values would take care of parsing the value and making
            // sure it is valid
            $results[] = $this->value($tokens);

            if ($this->peek($tokens)->type === Type::RIGHT_BRACKET) {
                break;
            }

            $this->move(Type::COMMA, $tokens);

            if ($this->peek($tokens)->type === Type::RIGHT_BRACKET) {
                throw new RuntimeException("Unexpected comma at the end of array");
            }
        }

        $this->move(Type::RIGHT_BRACKET, $tokens);

        return $results;
    }

    private function number(Token $token): int|float
    {
        // Seeing if the number is negative
        // If it is, we will temporarily remove '-' to ease our parsing
        $abs = str_starts_with($token->value, '-') ? substr($token->value, 1) : $token->value;

        // JSON standard doesn't allow multiple leading zeros in floats
        // valid - 0.1, invalid - 00.1
        if (str_starts_with($abs, '0') && mb_strlen($abs) > 1 && ($abs[1] !== '.')) {
            throw new RuntimeException("Integers cant have leading zeros");
        }

        return ctype_digit($token->value) ? (int) $token->value : (float) $token->value;
    }
}