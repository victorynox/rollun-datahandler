<?php

namespace rollun\datahandler\Processor\Factory;

use Interop\Container\ContainerInterface;
use rollun\datahandler\Factory\PluginAbstractFactoryAbstract;
use Zend\Validator\ValidatorInterface;
use Zend\Validator\ValidatorPluginManager;

/**
 * Class AbstractProcessorAbstractFactory
 * @package rollun\datahandler\Processor\Factory
 */
abstract class AbstractProcessorAbstractFactory extends PluginAbstractFactoryAbstract
{
    /**
     *  Config key for all processors config
     */
    const KEY = 'processors';

    /**
     *  Validator service that implement ValidatorInterface::class
     */
    const VALIDATOR_KEY = 'validator';

    /**
     * Options for validator
     */
    const VALIDATOR_OPTION_KEY = 'validatorOptions';

    /**
     * Get validator
     *
     * @param ContainerInterface $container
     * @param array $pluginOptions
     * @return ValidatorInterface|null
     */
    protected function getValidator(ContainerInterface $container, array $pluginOptions)
    {
        $validator = null;

        if (!isset($pluginOptions[self::VALIDATOR_KEY])) {
            return $validator;
        }

        $pluginRequestedName = $pluginOptions[self::VALIDATOR_KEY];
        $validatorPluginManager = $container->get(ValidatorPluginManager::class);

        if ($validatorPluginManager->has($pluginRequestedName)) {
            $validatorOptions = $pluginOptions[self::VALIDATOR_OPTION_KEY] ?? null;
            $validator = $validatorPluginManager->get($pluginRequestedName, $validatorOptions);
        } elseif ($container->has($pluginRequestedName)) {
            $validator = $container->get($pluginRequestedName);
        }

        return $validator;
    }

    /**
     * @param array $pluginOptions
     * @return array
     */
    protected function clearPluginOptions(array $pluginOptions)
    {
        unset($pluginOptions[self::VALIDATOR_KEY]);
        unset($pluginOptions[self::VALIDATOR_OPTION_KEY]);

        return $pluginOptions;
    }
}
