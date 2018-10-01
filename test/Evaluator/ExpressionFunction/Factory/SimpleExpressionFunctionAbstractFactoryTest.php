<?php

namespace rollun\test\datahandler\Evaluator\ExpressionFunction\Factory;

use InvalidArgumentException;
use rollun\datahandler\Evaluator\ExpressionFunction\Factory\SimpleExpressionFunctionAbstractFactory;
use rollun\datahandler\Evaluator\ExpressionFunction\LogicException;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;

class SimpleExpressionFunctionAbstractFactoryTest extends AbstractExpressionFunctionAbstractFactoryTest
{
    protected function setUp()
    {
        $this->object = new SimpleExpressionFunctionAbstractFactory();
    }

    public function testNegativeMissingCompilerOptionInvoke()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Missing 'compiler' option in config");
        $this->invoke();
    }

    public function testNegativeMissingEvaluatorOptionInvoke()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Missing 'evaluator' option in config");

        $requestedName = 'requestedServiceName';
        $container = $this->getContainer($requestedName, [
            'compiler' => 'functionCallback'
        ]);
        $container->setService('functionCallback', function ($value) {
            return $value . $value;
        });

        $this->object->__invoke($container, $requestedName);
    }

    public function testPositiveInvoke()
    {
        $functionName = 'functionName';
        $requestedName = 'requestedServiceName';
        $container = $this->getContainer($requestedName, [
            'compiler' => 'compilerFunctionCallback',
            'evaluator' => 'evaluatorFunctionCallback',
            'functionName' => $functionName,
        ]);
        $container->setService('compilerFunctionCallback', function ($value) {
            throw new LogicException();
        });
        $container->setService('evaluatorFunctionCallback', function ($value) {
            return $value . $value;
        });

        /** @var ExpressionFunction $expressionFunction */
        $expressionFunction = $this->object->__invoke($container, $requestedName);
        $this->assertEquals($expressionFunction->getName(), $functionName);
    }
}
