<?php

namespace App;

class Lexer
{
    /** @var array<Token> $tokens */
    protected array $tokens = [];

    protected int $cursor = 0;

    public function tokenize(string $input): array
    {
        if (empty($input)) {
            throw new InvalidTokenException("Empty Invalid JSON");
        }

        $length = mb_strlen($input);

        while ($this->cursor < $length) {
            $token = $input[$this->cursor];

            if ($token == '{') {
                $this->tokens[] = new Token(Type::LEFT_BRACE, $token);
            } elseif ($token == '"') {
                $this->tokens[] = new Token(Type::STRING, $this->lexStrings($input));
            } elseif ($token == ':') {
                $this->tokens[] = new Token(Type::COLON, $token);
            } elseif ($token == '}') {
                $this->tokens[] = new Token(Type::RIGHT_BRACE, $token);
            } elseif ($token == " ") {
                $this->cursor++;
                continue;
            } elseif ($token == ',') {
                $this->tokens[] = new Token(Type::COMMA, $token);
            } elseif (ctype_alpha($token)) {
                $this->tokens[] = $this->lexNullAndBooleans($input);
            } elseif (is_numeric($token) || $token == '-') {
                // The reason why we are checking for minus here and inside lexNumbers is
                // to accommodate -1 and 2.343e-3.
                // So numbers can start with minus and can contain minus in scientific notation
                $this->tokens[] = $this->lexNumbers($input);
            } elseif ($token == '[') {
                $this->tokens[] = new Token(Type::LEFT_BRACKET, $token);
            } elseif ($token == ']') {
                $this->tokens[] = new Token(Type::RIGHT_BRACKET, $token);
            } elseif ($token == "\n") {
                $this->cursor++;
                continue;
            } else {
                throw new InvalidTokenException("Found invalid token `{$input[$this->cursor]}` at position $this->cursor");
            }

            $this->cursor++;
        }

        return $this->tokens;
    }

    private function lexStrings(string $input): string
    {
        $result = '';
        $isEscaped = false;

        $this->cursor++;

        while ($this->cursor < mb_strlen($input)) {
            if ($input[$this->cursor] == '"' && ! $isEscaped) {
                break;
            } elseif ($input[$this->cursor] == '\\' && ! $isEscaped) {
                // Setting this flag to true would mean that the next character is escaped
                // This would help to avoid loop termination with escaped double quotes
                // That's why we have ($input == '"' && ! $isEscaped) condition in the if block
                $isEscaped = true;
            } elseif ($isEscaped) {
                $char = $input[$this->cursor];

                /**
                 * Extracting escaped character from the string
                 * Unicode have a specific format - \uXXXX
                 * where X is a hexadecimal number
                 * So, we can be sure to extract 4 characters after \u
                 *
                 * @see https://www.freeformatter.com/json-escape.html
                 */
                $result .= match ($char) {
                    '/' => '/',
                    'u' => $this->formatUnicode($input),
                    '"', '\\', 'b', 'f', 'n', 'r', 't' => sprintf("\\%s", $char),
                    default => throw new InvalidTokenException("Invalid unicode character at position $this->cursor"),
                };

                $isEscaped = false;

                // Here we are checking for invalid characters
                // These are not escaped characters
                // For example, JSON standard doesn't allow tab spaces between strings
                // file_get_contents replaces tab spaces in input with \t
                // Escaped tabs are two characters - "\" and "t" while invalid tabs are one character - \t (That's main difference)
                // So, if we find a tab space or new line etc., we throw an exception
            } elseif (in_array($input[$this->cursor], ["\n", "\r", "\t"])) {
                throw new InvalidTokenException("Invalid escape character at position $this->cursor");
            } else {
                $result .= $input[$this->cursor];
            }

            $this->cursor++;
        }

        // If string doesn't end with a double quote, it is invalid
        // We can do that as a condition in the while loop, but it would
        // have introduced more edge cases in case of escaped double quotes
        // making loop termination condition more complex
        // { "key": "value\"" } is a valid JSON
        if ($input[$this->cursor] != '"') {
            throw new InvalidTokenException("Invalid termination of string");
        }

        return $result;
    }

    private function lexNullAndBooleans(string $input): Token
    {
        foreach (['true' => Type::BOOLEAN, 'false' => Type::BOOLEAN, 'null' => Type::NULL] as $keyword => $type) {
            if (substr($input, $this->cursor, strlen($keyword)) === $keyword) {
                $this->cursor += strlen($keyword) - 1;
                return new Token($type, $keyword);
            }
        }

        // This works because only valid alpha characters without quotes are null, true, false
        // Any other character would be invalid
        throw new InvalidTokenException("Found invalid token `{$input[$this->cursor]}` at position $this->cursor");
    }

    private function lexNumbers(string $input): ?Token
    {
        $number = '';

        // Accommodating scientific notation e.g. 1.2e-3
        while (is_numeric($input[$this->cursor]) || in_array($input[$this->cursor], ['.', 'e', 'E', '+', '-'])) {
            $number .= $input[$this->cursor];
            $this->cursor++;
        }

        $this->cursor--;

        return new Token(Type::NUMBER, $number);
    }

    private function formatUnicode(string $input): string
    {
        $code = substr($input, $this->cursor + 1, 4);

        // Checking if the code is a valid hexadecimal number
        if (! ctype_xdigit($code)) {
            throw new InvalidTokenException("Invalid unicode character at position $this->cursor");
        }

        $this->cursor += 4;

        // Pack() would convert the input data into binary format
        // "H*" means consider the input as hexadecimal using big-endian byte order
        // UTF-16BE is the encoding format for the output
        // "BE" stands for big-endian
        // This would convert something like 0061 to a character "a"
        return mb_convert_encoding(pack('H*', $code), 'UTF-8', 'UTF-16BE');
    }
}