<?php

namespace rollun\datahandler\Processor;

use LogicException;
use InvalidArgumentException;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\SyntaxError;
use Zend\Validator\ValidatorInterface;

class Evaluation extends AbstractProcessor
{
    protected $expressionLanguage = null;

    protected $resultColumn;

    protected $expression;

    public function __construct(
        array $options = null,
        ValidatorInterface $validator = null,
        ExpressionLanguage $expressionLanguage = null
    ) {
        parent::__construct($options, $validator);
        $this->expressionLanguage = $expressionLanguage;
    }

    public function setExpression($expression)
    {
        $this->expression = $expression;
    }

    public function getExpression()
    {
        if ($this->expression === null) {
            throw new InvalidArgumentException("Missing 'expression' in options");
        }

        return $this->expression;
    }

    public function setResultColumn($resultColumn)
    {
        $this->resultColumn = $resultColumn;
    }

    public function getResultColumn()
    {
        if ($this->resultColumn === null) {
            throw new InvalidArgumentException("Missing 'resultColumn' in options");
        }

        return $this->resultColumn;
    }

    public function getExpressionLanguage()
    {
        if ($this->expressionLanguage === null) {
            $this->expressionLanguage = new ExpressionLanguage();
        }

        return $this->expressionLanguage;
    }

    public function doProcess(array $value)
    {
        $resultColumn = $this->getResultColumn();

        try {
            $evaluatedResult = $this->getExpressionLanguage()->evaluate($this->getExpression(), $value);
        } catch (SyntaxError $e) {
            throw new LogicException('Evaluating syntax error: ' . $e->getMessage());
        }

        $value[$resultColumn] = $evaluatedResult;

        return $value;
    }
}
