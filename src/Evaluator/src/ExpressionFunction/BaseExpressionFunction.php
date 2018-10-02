<?php

namespace rollun\datahandler\Evaluator\ExpressionFunction;

use InvalidArgumentException;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;

/**
 * Class BaseExpressionFunction
 * @package rollun\datahandler\Evaluator\ExpressionFunction
 */
class BaseExpressionFunction extends ExpressionFunction
{
    /**
     * @return callable|\Closure
     */
    public function getEvaluator()
    {
        $compiler = $this->getCompiler();

        if (!is_callable($compiler)) {
            throw new InvalidArgumentException("Compiler is not valid");
        }

        return function() use ($compiler) {
            $args = func_get_args();
            // first argument is $arguments, we do not need it
            array_shift($args);

            $result = null;
            eval(sprintf('$result = %s;', $compiler($args)));

            return $result;
        };
    }
}
