<?php

namespace rollun\datahandler\Evaluator\ExpressionFunction\Factory;

use InvalidArgumentException;
use Interop\Container\ContainerInterface;
use rollun\callback\Callback\Callback;
use rollun\datahandler\Evaluator\ExpressionFunction\Callback as CallbackExpressionFunction;

/**
 * Create and return instance of Callback function expressions
 *
 * This Factory depends on Container (which should return an 'config' as array)
 *
 * Config example:
 * <code>
 * AbstractExpressionFunctionAbstractFactory::KEY => [
 *      CallbackExpressionFunctionAbstractFactory::class =>
 *          'callbackExpressionFunctionServiceName1' => [
 *              'class' => Callback::class, // default value
 *              'callbackService' => 'callbackServiceName1',
 *              'callbackMethod' => 'callbackMethodName1',
 *              'functionName' => 'functionName1',
 *          ],
 *          'callbackExpressionFunctionServiceName2' => [
 *              //...
 *          ],
 *      ]
 * ]
 * </code>
 *
 * Class ExpressionEvaluatorFactory
 * @package rollun\datahandler\Evaluator
 */
class CallbackExpressionFunctionAbstractFactory extends AbstractExpressionFunctionAbstractFactory
{
    /**
     * Parent class for function
     */
    const DEFAULT_CLASS = CallbackExpressionFunction::class;

    /**
     * Config key for callback service
     */
    const CALLBACK_SERVICE_KEY = 'callbackService';

    /**
     * Config key for callback method
     */
    const CALLBACK_METHOD_KEY = 'callbackMethod';

    /**
     * Config key for function name
     */
    const FUNCTION_NAME_KEY = 'functionName';

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig = $this->getServiceConfig($container, $requestedName);

        /** @var CallbackExpressionFunction $class */
        $class = $this->getClass($serviceConfig);

        if (!isset($serviceConfig[self::CALLBACK_SERVICE_KEY])) {
            throw new InvalidArgumentException("Missing 'callbackMethod' option in config");
        }

        $callbackService = $container->get($serviceConfig[self::CALLBACK_SERVICE_KEY]);
        $callbackMethod = $serviceConfig[self::CALLBACK_METHOD_KEY] ?? '__invoke';
        $functionName = $serviceConfig[self::FUNCTION_NAME_KEY] ?? $requestedName;
        $callback = new Callback([$callbackService, $callbackMethod]);

        return new $class($callback, $functionName);
    }
}
