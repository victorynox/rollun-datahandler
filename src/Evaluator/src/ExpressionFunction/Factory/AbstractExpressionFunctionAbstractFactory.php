<?php

namespace rollun\datahandler\Evaluator\ExpressionFunction\Factory;

use Interop\Container\ContainerInterface;
use InvalidArgumentException;
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
     * Parent class for function
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
        return isset($container->get('config')[self::KEY][static::class][$requestedName]);
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
