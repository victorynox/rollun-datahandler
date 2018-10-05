<?php

namespace rollun\datahandler\Filter;

use rollun\datahandler\Filter\Factory\EvaluationFilterAbstractFactory;
use rollun\datahandler\Filter\Factory\SimpleFilterAbstractFactory;

/**
 * The configuration provider for filters
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
                EvaluationFilterAbstractFactory::class,
                SimpleFilterAbstractFactory::class,
            ]
        ];
    }
}
