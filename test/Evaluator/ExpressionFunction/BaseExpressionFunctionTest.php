<?php

namespace rollun\test\datahandler\Evaluator\ExpressionFunction;

use PHPUnit\Framework\TestCase;
use rollun\datahandler\Evaluator\ExpressionFunction\BaseExpressionFunction;

/**
 * Class BaseExpressionFunctionTest
 * @package rollun\test\datahandler\Evaluator\ExpressionFunction
 */
class BaseExpressionFunctionTest extends TestCase
{
    public function testPositiveWithEvaluator()
    {
        $evaluator = function ($arguments, $value) {
            return $value . $value;
        };
        $compiler = function ($value) {
            return "'$value' . '$value'";
        };
        $object = new BaseExpressionFunction('duplicate', $compiler, $evaluator);

        $value = 'a';
        $result = null;
        $evaluated = $object->getEvaluator()([], $value);
        $compiled = $object->getCompiler()($value);
        eval(sprintf('$result = %s;', $compiled));

        $this->assertEquals($result, 'aa');
        $this->assertEquals($evaluated, 'aa');
    }

    public function testPositiveWithoutEvaluator()
    {
        $compiler = function ($value) {
            return "'$value' . '$value'";
        };
        $object = new BaseExpressionFunction('duplicate', $compiler);

        $value = 'a';
        $result = null;
        $evaluated = $object->getEvaluator()([], $value);
        $compiled = $object->getCompiler()($value);
        eval(sprintf('$result = %s;', $compiled));

        $this->assertEquals($result, 'aa');
        $this->assertEquals($evaluated, 'aa');
    }
}
