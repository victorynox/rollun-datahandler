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
 *      PHPExpressionFunctionAbstractProvider::class =>
 *          'phpExpressionFunctionServiceName1' => [
 *              'class' => ExpressionFunction::class, // default value
 *              'phpFunctionName' => 'My\functions',
 *              'expressionFunctionName' => '', // alias function in expression language
 *          ],
 *          'phpExpressionFunctionServiceName2' => [
 *              //...
 *          ],
 *      ]
 * ]
 * </code>
 *
 * Class ExpressionEvaluatorFactory
 * @package rollun\datahandler\Evaluator
 */
class   PHPExpressionFunctionAbstractFactory extends AbstractExpressionFunctionAbstractFactory
{
    const DEFAULT_CLASS = ExpressionFunction::class;

    const EXPRESSION_FUNCTION_NAME_KEY = 'functionNamespace';

    const PHP_FUNCTION_NAME_KEY = 'functionName';

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig = $this->getServiceConfig($container, $requestedName);
        $class = $this->getClass($serviceConfig);

        if (!isset($serviceConfig[self::PHP_FUNCTION_NAME_KEY])) {
            throw new InvalidArgumentException("Missing 'phpFunctionName' option in config");
        }

        $phpFunctionName = $serviceConfig[self::PHP_FUNCTION_NAME_KEY];
        $expressionFunctionName = $serviceConfig[self::EXPRESSION_FUNCTION_NAME_KEY] ?? $phpFunctionName;

        /** @var ExpressionFunction $expressionFunction */
        $expressionFunction = $class::fromPhp($phpFunctionName, $expressionFunctionName);

        return $expressionFunction;
    }
}
