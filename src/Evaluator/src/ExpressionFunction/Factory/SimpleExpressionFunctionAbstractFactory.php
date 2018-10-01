<?php

namespace rollun\datahandler\Evaluator\ExpressionFunction\Factory;

use InvalidArgumentException;
use Interop\Container\ContainerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;

/**
 * Create and return instance of ExpressionFunction
 *
 * This Factory depends on Container (which should return an 'config' as array)
 *
 * Config example:
 * <code>
 * AbstractExpressionFunctionAbstractFactory::KEY => [
 *      SimpleExpressionFunctionAbstractFactory::class =>
 *          'simpleExpressionFunctionServiceName1' => [
 *              'class' => ExpressionFunction::class, // default value
 *
 *              'functionName' => 'functionName1',
 *              // function ($str) {
 *              //      return sprintf('(is_string(%1$s) ? strtolower(%1$s) : %1$s)', $str);
 *              // }
 *              'compilerService' => 'compilerServiceName1', service name which is callable
 *
 *              // function ($arguments, $str) {
 *              //      if (!is_string($str)) {
 *              //          return $str;
 *              //      }
 *              //
 *              //      return strtolower($str);
 *              // }
 *              'evaluatorService' => 'evaluatorServiceName1', service name which is callable
 *          ],
 *          'simpleExpressionFunctionServiceName2' => [
 *              //...
 *          ],
 *      ]
 * ]
 * </code>
 *
 * Class ExpressionEvaluatorFactory
 * @package rollun\datahandler\Evaluator
 */
class SimpleExpressionFunctionAbstractFactory extends AbstractExpressionFunctionAbstractFactory
{
    /**
     * Config key for function compiler
     */
    const COMPILER_KEY = 'compiler';

    /**
     * Config key for function evaluator
     */
    const EVALUATOR_KEY = 'evaluator';

    /**
     * Config key for function name
     */
    const FUNCTION_NAME_KEY = 'functionName';

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ExpressionFunction
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig = $this->getServiceConfig($container, $requestedName);

        $functionName = $serviceConfig[self::FUNCTION_NAME_KEY] ?? $requestedName;
        $compiler = $this->getCallbackOption($container, $serviceConfig, self::COMPILER_KEY);
        $evaluator = $this->getCallbackOption($container, $serviceConfig, self::EVALUATOR_KEY);

        /** @var ExpressionFunction $class */
        $class = $this->getClass($serviceConfig);

        return new $class($functionName, $compiler, $evaluator);
    }

    /**
     * @param ContainerInterface $container
     * @param $serviceConfig
     * @param $configKey
     * @return mixed
     */
    protected function getCallbackOption(ContainerInterface $container, $serviceConfig, $configKey)
    {
        if (!isset($serviceConfig[$configKey])) {
            throw new InvalidArgumentException("Missing '$configKey' option in config");
        }

        $evaluator = $container->get($serviceConfig[$configKey]);

        if (is_callable($evaluator)) {
            return $evaluator;
        }

        throw new InvalidArgumentException(ucfirst($configKey) . " must be callable");
    }
}
