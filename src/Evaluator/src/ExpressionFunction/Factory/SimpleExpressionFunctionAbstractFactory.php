<?php

namespace rollun\datahandler\Evaluator\ExpressionFunction\Factory;

use InvalidArgumentException;
use Interop\Container\ContainerInterface;
use rollun\datahandler\Evaluator\ExpressionFunction\BaseExpressionFunction;
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
 *              'class' => ExpressionFunction::class,
 *              'functionName' => 'functionName1', // optional, default 'simpleExpressionFunctionServiceName1'
 *
 *              // compiler callable example
 *              // function ($str) {
 *              //      return sprintf('(is_string(%1$s) ? strtolower(%1$s) : %1$s)', $str);
 *              // }
 *              'compiler' => 'compilerServiceName1', service name which is callable
 *
 *              // evaluator callable example
 *              // function ($arguments, $str) {
 *              //      if (!is_string($str)) {
 *              //          return $str;
 *              //      }
 *              //
 *              //      return strtolower($str);
 *              // }
 *              'evaluator' => 'evaluatorServiceName1', service name which is callable
 *          ],
 *          'simpleExpressionFunctionServiceName2' => [
 *              //...
 *          ],
 *      ]
 * ]
 * </code>
 *
 * Class SimpleExpressionFunctionAbstractFactory
 * @package rollun\datahandler\Evaluator
 */
class SimpleExpressionFunctionAbstractFactory extends AbstractExpressionFunctionAbstractFactory
{
    /**
     * Default caused class
     */
    const DEFAULT_CLASS = BaseExpressionFunction::class;

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

        if (!isset($serviceConfig[self::COMPILER_KEY])) {
            throw new InvalidArgumentException("Missing 'compiler' option in config");
        }

        $functionName = $serviceConfig[self::FUNCTION_NAME_KEY] ?? $requestedName;
        $compiler = $container->get($serviceConfig[self::COMPILER_KEY]);
        $evaluator = isset($serviceConfig[self::COMPILER_KEY])
            ? $container->get($serviceConfig[self::COMPILER_KEY])
            : null;

        /** @var ExpressionFunction $class */
        $class = $this->getClass($serviceConfig);

        return new $class($functionName, $compiler, $evaluator);
    }
}
