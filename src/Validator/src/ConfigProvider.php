<?php

namespace rollun\datahandler\Validator;

use rollun\datahandler\Validator\Factory\ArrayDecoratorAbstractFactory;
use rollun\datahandler\Validator\Factory\SimpleValidatorAbstractFactory;
use rollun\datahandler\Validator\Factory\ThrowableDecoratorAbstractFactory;

/**
 * The configuration provider for validators
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
                ArrayDecoratorAbstractFactory::class,
                SimpleValidatorAbstractFactory::class,
                ThrowableDecoratorAbstractFactory::class,
            ]
        ];
    }
}
