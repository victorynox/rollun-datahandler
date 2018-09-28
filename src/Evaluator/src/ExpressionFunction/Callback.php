<?php

namespace rollun\datahandler\Evaluator\ExpressionFunction;

use LogicException;
use rollun\callback\Callback\Callback as SerializableCallback;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;

class Callback extends ExpressionFunction
{
    protected $callback;

    public function __construct(SerializableCallback $callback, string $functionName)
    {
        $this->callback = $callback;
        parent::__construct($functionName, $this->getCompiler(), $this->getEvaluator());
    }

    public function getCompiler()
    {
        $callback = $this->callback;

        $compiler = function ($value) use ($callback) {
            throw new LogicException("Callback expression function can't be compiled");
        };

        return $compiler;
    }

    public function getEvaluator()
    {
        $callback = $this->callback;

        $evaluator = function ($arguments, $value) use ($callback) {
            return $callback($value);
        };

        return $evaluator;
    }
}
