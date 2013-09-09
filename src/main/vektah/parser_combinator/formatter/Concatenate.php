<?php

namespace vektah\parser_combinator\formatter;

use vektah\parser_combinator\exception\GrammarException;
use vektah\parser_combinator\Input;
use vektah\parser_combinator\parser\Parser;

class Concatenate implements Parser
{
    private $parser;
    private $glue;

    public function __construct(Parser $parser, $glue = '')
    {
        $this->glue = $glue;
        $this->parser = $parser;
    }

    private function implode($glue, array $array)
    {
        foreach ($array as $key => $item) {
            if (is_array($item)) {
                $array[$key] = $this->implode($glue, $item) . $glue;
            }
        }

        return implode($glue, $array);
    }

    public function parse(Input $input)
    {
        $result = $this->parser->parse($input);

        if (is_array($result->data)) {
            $result->data = $this->implode($this->glue, $result->data);
            return $result;
        } else {
            return $result;
        }
    }
}
