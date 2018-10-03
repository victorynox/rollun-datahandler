<?php

namespace rollun\datahandler\Processor;

use rollun\datahandler\Processor\Factory\EvaluationProcessorAbstractFactory;
use rollun\datahandler\Processor\Factory\FilterApplierProcessorAbstractFactory;
use rollun\datahandler\Processor\Factory\ProcessorPluginManagerFactory;
use rollun\datahandler\Processor\Factory\SimpleProcessorAbstractFactory;

/**
 * The configuration provider for processor
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
                EvaluationProcessorAbstractFactory::class,
                FilterApplierProcessorAbstractFactory::class,
                ProcessorPluginManagerFactory::class,
                SimpleProcessorAbstractFactory::class,
            ]
        ];
    }
}
