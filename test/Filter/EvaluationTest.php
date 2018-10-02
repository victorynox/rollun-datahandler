<?php

namespace rollun\test\datahandler\Filter;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use rollun\datahandler\Filter\Evaluation;

class EvaluationTest extends TestCase
{
    public function testPositive()
    {
        $filter = new Evaluation([
            'expression' => "value ~ 'cd'",
        ]);

        $result = $filter->filter('ab');

        $this->assertEquals($result, 'abcd');
    }

    public function testNegativeMissingExpression()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Missing 'expression' in options");
        $filter = new Evaluation();
        $filter->filter('ab');
    }
}
