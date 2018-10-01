<?php

namespace rollun\datahandler\Evaluator;

use Interop\Container\ContainerInterface;
use InvalidArgumentException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Create and return instance of ExpressionEvaluator
 * You can add function expressions to expression evaluation through function expression provider services
 * or directly through function expression services
 *
 * This Factory depends on Container (which should return an 'config' as array)
 *
 * Config example:
 * <code>
 * ExpressionEvaluatorAbstractFactory::class => [
 *      'expressionEvaluatorServiceName1' => [
 *          'class' => ExpressionEvaluatorFactory::class, // default value
 *          'functionExpressionProviders' => [ // optional
 *              'functionExpressionProviderServiceName1',
 *              'functionExpressionProviderServiceName2',
 *              //...
 *          ],
 *          'functionExpressions' => [ // optional
 *              'functionExpressionServiceName1',
 *              'functionExpressionServiceName2',
 *              //...
 *          ],
 *      ],
 *      'expressionEvaluatorServiceName2' => [
 *          //...
 *      ]
 * ]
 * </code>
 *
 * Class ExpressionEvaluatorFactory
 * @package rollun\datahandler\Evaluator
 */
class ExpressionEvaluatorAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Parent class for expression evaluation
     */
    const DEFAULT_CLASS = ExpressionEvaluator::class;

    /**
     * Config key for caused class
     */
    const CLASS_KEY = 'class';

    /**
     * Config for function expression providers
     */
    const FUNCTION_EXPRESSION_PROVIDERS_KEY = 'functionExpressionProviders';

    /**
     * Config for function expressions
     */
    const FUNCTION_EXPRESSIONS_KEY = 'functionExpressions';

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return !is_null($this->getServiceConfig($container, $requestedName));
    }

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ExpressionEvaluator
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig = $container->get('config')[self::class][$requestedName];
        $class = $this->getClass($serviceConfig);

        /** @var ExpressionEvaluator $expressionEvaluator */
        $expressionEvaluator = new $class();

        $functionExpressionServiceNames = $serviceConfig[self::FUNCTION_EXPRESSIONS_KEY] ?? [];
        $functionExpressionProviderServiceNames = $serviceConfig[self::FUNCTION_EXPRESSION_PROVIDERS_KEY] ?? [];

        if (is_array($functionExpressionProviderServiceNames)) {
            foreach ($functionExpressionProviderServiceNames as $functionExpressionProviderServiceName) {
                $functionExpressionProvider = $container->get($functionExpressionProviderServiceName);
                $expressionEvaluator->registerProvider($functionExpressionProvider);
            }
        } else {
            throw new InvalidArgumentException("Option 'functionExpressionProviders' is invalid");
        }

        if (is_array($functionExpressionServiceNames)) {
            foreach ($functionExpressionServiceNames as $functionExpressionServiceName) {
                $functionExpression = $container->get($functionExpressionServiceName);
                $expressionEvaluator->addFunction($functionExpression);
            }
        } else {
            throw new InvalidArgumentException("Option 'functionExpressions' is invalid");
        }

        return $expressionEvaluator;
    }

    /**
     * Get caused class
     *
     * @param array $serviceConfig
     * @return mixed
     */
    public function getClass(array $serviceConfig)
    {
        if (!isset($serviceConfig[self::CLASS_KEY])) {
            return self::DEFAULT_CLASS;
        }

        if (!is_a($serviceConfig[self::CLASS_KEY], static::DEFAULT_CLASS, true)) {
            throw new \InvalidArgumentException(
                'Caused class must implement or extend ' . static::DEFAULT_CLASS
            );
        }

        return $serviceConfig[self::CLASS_KEY];
    }

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @return null|array
     */
    public function getServiceConfig(ContainerInterface $container, $requestedName)
    {
        $config = $container->get('config');
        return $config[self::class][$requestedName] ?? null;
    }
}
