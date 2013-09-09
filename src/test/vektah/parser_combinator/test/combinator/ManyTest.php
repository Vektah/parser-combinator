<?php

namespace vektah\parser_combinator\test\combinator;

use PHPUnit_Framework_TestCase as TestCase;
use vektah\parser_combinator\Input;
use vektah\parser_combinator\combinator\Many;
use vektah\parser_combinator\exception\GrammarException;
use vektah\parser_combinator\parser\RegexParser;
use vektah\parser_combinator\parser\StringParser;

class ManyTest extends TestCase
{
    public function testSingle()
    {
        $many = new Many(['asdf']);

        $this->assertEquals(['asdf'], $many->parse(new Input('asdf'))->data);
        $this->assertEquals(['asdf', 'asdf'], $many->parse(new Input('asdfasdf'))->data);
    }

    public function testMultiple()
    {
        $many = new Many(['asdf', 'hjkl']);

        $this->assertEquals(['hjkl'], $many->parse(new Input('hjkl'))->data);
        $this->assertEquals(['hjkl', 'asdf'], $many->parse(new Input('hjklasdf'))->data);
    }

    public function testMin()
    {
        $many = new Many([new StringParser('asdf')], 2);

        $this->assertEquals('At line 1 offset 5: Expected 2 elements, but found 1', $many->parse(new Input('asdf'))->errorMessage);
    }

    public function testMax()
    {
        $many = new Many([new StringParser('asdf')], 0, 2);

        $input = new Input('asdfasdfasdf');
        $this->assertEquals(['asdf', 'asdf'], $many->parse($input)->data);
        $this->assertEquals(['asdf'], $many->parse($input)->data);
    }

    public function testZeroWidthMatch()
    {
        $many = new Many([new RegexParser('/^/')]);

        $this->setExpectedException(GrammarException::_CLASS);
        $many->parse(new Input('asdf'));
    }
}
