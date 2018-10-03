<?php

namespace rollun\datahandler\Validator\Decorator\Factory;

use Interop\Container\ContainerInterface;
use InvalidArgumentException;
use rollun\datahandler\Factory\PluginAbstractFactoryAbstract;
use Zend\Validator\ValidatorInterface;
use Zend\Validator\ValidatorPluginManager;

/**
 * Class AbstractValidatorDecoratorAbstractFactory
 * @package rollun\datahandler\Validator\Factory
 */
abstract class AbstractValidatorDecoratorAbstractFactory extends PluginAbstractFactoryAbstract
{
    /**
     * Common namespace name for plugin config
     */
    const KEY = 'validators';

    /**
     *  Config key for decorated validator
     */
    const VALIDATOR_KEY = 'validator';

    /**
     * Config key for options for decorated validator
     */
    const VALIDATOR_OPTION_KEY = 'validatorOptions';

    /**
     * @param ContainerInterface $container
     * @param array $decoratorOptions
     * @return ValidatorInterface
     */
    public function getDecoratedValidator(ContainerInterface $container, array $decoratorOptions)
    {
        if (!isset($decoratorOptions[self::VALIDATOR_KEY])) {
            throw new InvalidArgumentException("Missing 'validator' option");
        }

        $validatorRequestedName = $decoratorOptions[self::VALIDATOR_KEY];
        $validatorPluginManager = $container->get(ValidatorPluginManager::class);
        $validatorOptions = $pluginOptions[self::VALIDATOR_OPTION_KEY] ?? null;

        if (($validatorPluginManager instanceof ValidatorPluginManager)
            && $validatorPluginManager->has($validatorRequestedName)) {

            $validator = $validatorPluginManager->get($validatorRequestedName, $validatorOptions);
        } elseif ($container->has($validatorRequestedName)) {
            $validator = $container->get($validatorRequestedName);
        } else {
            throw new InvalidArgumentException(
                "Can't create validator service with name '$validatorRequestedName'"
            );
        }

        return clone $validator;
    }

    /**
     * Remove extra options
     *
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
