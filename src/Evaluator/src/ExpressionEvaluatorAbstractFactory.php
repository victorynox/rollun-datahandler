<?php

namespace rollun\datahandler\Evaluator;

use Interop\Container\ContainerInterface;
use InvalidArgumentException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Create and return instance of ExpressionEvaluator
 *
 * This Factory depends on Container (which should return an 'config' as array)
 *
 * Config example:
 * <code>
 * ExpressionEvaluatorAbstractFactory::class => [
 *      'expressionEvaluatorServiceName1' => [
 *          'class' => ExpressionEvaluatorFactory::class, // default value
 *          'functionExpressionProviders' => [
 *              'functionExpressionProviderServiceName1',
 *              'functionExpressionProviderServiceName2',
 *              //...
 *          ]
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
     * Parent class for plugin
     */
    const DEFAULT_CLASS = ExpressionEvaluator::class;

    /**
     * Config key for caused class
     */
    const CLASS_KEY = 'class';

    /**
     * Config for plugin managers
     */
    const FUNCTION_EXPRESSION_PROVIDERS_KEY = 'functionExpressionProviders';

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return isset($container->get('config')[self::class][$requestedName]);
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

        if (!isset($serviceConfig[self::FUNCTION_EXPRESSION_PROVIDERS_KEY])) {
            throw new InvalidArgumentException("Missing 'pluginManagers' option in config");
        } elseif (!is_array($serviceConfig[self::FUNCTION_EXPRESSION_PROVIDERS_KEY])) {
            throw new InvalidArgumentException("Option 'pluginManagers' is invalid");
        }

        $functionExpressionProviderServiceNames = $serviceConfig[self::FUNCTION_EXPRESSION_PROVIDERS_KEY];

        foreach ($functionExpressionProviderServiceNames as $functionExpressionProviderServiceName) {
            $functionExpressionProvider = $container->get($functionExpressionProviderServiceName);
            $expressionEvaluator->registerProvider($functionExpressionProvider);
        }

        return new $class();
    }

    /**
     * Get caused class
     *
     * @param array $serviceConfig
     * @return mixed
     */
    protected function getClass(array $serviceConfig)
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
}
