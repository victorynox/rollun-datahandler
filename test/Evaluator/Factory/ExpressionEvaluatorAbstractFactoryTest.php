<?php

namespace rollun\test\datahandler\Evaluator\Factory;

use PHPUnit\Framework\TestCase;
use rollun\datahandler\Evaluator\ExpressionEvaluator;
use rollun\datahandler\Evaluator\ExpressionEvaluatorAbstractFactory;
use rollun\datahandler\Evaluator\ExpressionFunction\LogicException;
use rollun\datahandler\Evaluator\ExpressionFunction\Providers\Plugin;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Zend\Filter\FilterPluginManager;
use Zend\ServiceManager\ServiceManager;
use Zend\Validator\ValidatorPluginManager;

class ExpressionEvaluatorAbstractFactoryTest extends TestCase
{
    /**
     * @var ExpressionEvaluatorAbstractFactory
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new ExpressionEvaluatorAbstractFactory();
    }

    public function testCanCreate()
    {
        $requestedName = 'requestedServiceName';
        $container = $this->getContainer($requestedName);

        $this->assertTrue($this->object->canCreate($container, $requestedName));
        $this->assertFalse($this->object->canCreate($container, 'BlaBlaBla'));
    }

    public function testCreateWithExpressionFunctions()
    {
        $requestedName = 'requestedServiceName';
        $container = $this->getContainer($requestedName, [
            'functionExpressions' => [
                'expressionFunction1',
                'expressionFunction2',
            ]
        ]);
        $container->setService('expressionFunction1', ExpressionFunction::fromPhp('trim'));
        $container->setService('expressionFunction2', ExpressionFunction::fromPhp('ucfirst'));

        /** @var ExpressionEvaluator $expressionEvaluator */
        $expressionEvaluator = $this->object->__invoke($container, $requestedName);
        $this->assertTrue($this->isExpressionValid($expressionEvaluator, "trim('   dsadsa ')", 'dsadsa'));
        $this->assertTrue($this->isExpressionValid($expressionEvaluator, "ucfirst('as')", 'As'));
    }

    public function testCreateWithExpressionFunctionProviders()
    {
        $requestedName = 'requestedServiceName';
        $container = $this->getContainer($requestedName, [
            'functionExpressionProviders' => [
                'expressionFunctionProvider1',
                'expressionFunctionProvider2',
            ]
        ]);
        $container->setService(
            'expressionFunctionProvider1',
            new Plugin(new FilterPluginManager($container), ['stringTrim'])
        );
        $container->setService(
            'expressionFunctionProvider2',
            new Plugin(new ValidatorPluginManager($container), ['digits'])
        );

        /** @var ExpressionEvaluator $expressionEvaluator */
        $expressionEvaluator = $this->object->__invoke($container, $requestedName);
        $this->assertTrue($this->isExpressionValid($expressionEvaluator, "stringTrim('   dsadsa ')", 'dsadsa'));
        $this->assertTrue($this->isExpressionValid($expressionEvaluator, "digits('1234a')", false));
    }

    /**
     * @param $requestedName
     * @param array $serviceConfig
     * @return ServiceManager
     */
    protected function getContainer($requestedName, $serviceConfig = [])
    {
        $container = new ServiceManager();
        $container->setService('config', [
            get_class($this->object) => [
                $requestedName => $serviceConfig
            ]
        ]);

        return $container;
    }

    /**
     * @param array $serviceConfig
     * @return \rollun\datahandler\Evaluator\ExpressionEvaluator
     */
    protected function invoke($serviceConfig = [])
    {
        $requestedName = 'requestedServiceName';
        $container = $this->getContainer($requestedName, $serviceConfig);
        return $this->object->__invoke($container, $requestedName, null);
    }

    protected function isExpressionValid(ExpressionEvaluator $expressionEvaluator, $expression, $expectedEvaluation)
    {
        try {
            $expressionEvaluator->compile($expression);
        } catch (LogicException $e) {
            if ($e->getCode() != LogicException::COMPILER_NOT_SUPPORTED) {
                return false;
            }
        }

        try {
            $evaluation = $expressionEvaluator->evaluate($expression);
            return $evaluation === $expectedEvaluation;
        } catch (\Exception $e) {
            return false;
        }
    }
}
