<?php

namespace rollun\datahandler\Evaluator\ExpressionFunction\Factory;

use Interop\Container\ContainerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Class AbstractExpressionFunctionAbstractFactory
 * @package rollun\datahandler\Evaluator\ExpressionFunction\Factory
 */
abstract class AbstractExpressionFunctionAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Config key for function expression configs
     */
    const KEY = 'functionExpressions';

    /**
     * Default caused class
     */
    const DEFAULT_CLASS = ExpressionFunction::class;

    /**
     * Config key for caused class
     */
    const CLASS_KEY = 'class';

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
     * @param array $serviceConfig
     * @param bool $required
     * @return string
     */
    public function getClass(array $serviceConfig, $required = false)
    {
        if (!isset($serviceConfig[self::CLASS_KEY])) {
            if (!$required) {
                return self::DEFAULT_CLASS;
            }

            throw new \InvalidArgumentException("There is no 'class' config for plugin in config");
        } else if (!is_a($serviceConfig[self::CLASS_KEY], static::DEFAULT_CLASS, true)) {
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
        return $config[static::KEY][static::class][$requestedName] ?? null;
    }
}
