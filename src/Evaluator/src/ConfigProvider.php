<?php

namespace rollun\datahandler\Evaluator;

use rollun\callback\Callback\Factory\CallbackAbstractFactoryAbstract;
use rollun\datahandler\Evaluator\ExpressionFunction\Factory\PHPExpressionFunctionAbstractFactory;
use rollun\datahandler\Evaluator\ExpressionFunction\Factory\SimpleExpressionFunctionAbstractFactory;
use rollun\datahandler\Evaluator\ExpressionFunction\Providers\PluginFunctionExpressionProviderAbstractFactory;

/**
 * The configuration provider for evaluation
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
        ];
    }

    /**
     * Returns the templates configuration
     *
     * @return array
     */
    public function getDependencyConfig()
    {
        return [
            'abstract_factories' => [
                CallbackAbstractFactoryAbstract::class,
                CallbackAbstractFactoryAbstract::class,
                PHPExpressionFunctionAbstractFactory::class,
                SimpleExpressionFunctionAbstractFactory::class,
                PluginFunctionExpressionProviderAbstractFactory::class,
            ]
        ];
    }
}
