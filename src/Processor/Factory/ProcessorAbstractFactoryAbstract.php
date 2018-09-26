<?php

namespace rollun\datanadler\Processor\Factory;

use Interop\Container\ContainerInterface;
use rollun\datanadler\Factory\PluginAbstractFactoryAbstract;
use Zend\Validator\ValidatorInterface;
use Zend\Validator\ValidatorPluginManager;

/**
 * Class ProcessorAbstractFactoryAbstract
 * @package rollun\datanadler\Processor\Factory
 */
abstract class ProcessorAbstractFactoryAbstract extends PluginAbstractFactoryAbstract
{
    const KEY = 'processors';

    /**
     *  Validator service that implement ValidatorInterface::class
     */
    const VALIDATOR_KEY = 'validator';

    /**
     * Options for validator. Need to create processor via options that come through __invoke method
     */
    const VALIDATOR_OPTION_KEY = 'validatorOptions';

    /**
     * Get validator
     *
     * @param ContainerInterface $container
     * @param $serviceConfig
     * @param array|null $options
     * @return ValidatorInterface|null
     */
    protected function getValidator(ContainerInterface $container, $serviceConfig, array $options = null)
    {
        $validator = null;

        if (isset($options[self::VALIDATOR_KEY])) {
            $validatorPluginManager = $container->get(ValidatorPluginManager::class);
            $validatorOptions = $options[self::VALIDATOR_OPTION_KEY] ?? [];
            $validator = $validatorPluginManager->get($options[self::VALIDATOR_KEY], $validatorOptions);
        }

        if (isset($serviceConfig[self::VALIDATOR_KEY])) {
            if (isset($validator)) {
                throw new \LogicException('Validator config already set in options');
            }

            $validator = $container->get($serviceConfig[self::VALIDATOR_KEY]);
        }

        if ($validator !== null && !($validator instanceof ValidatorInterface)) {
            throw new \InvalidArgumentException('Validator class must implement ' . ValidatorInterface::class);
        }

        return $validator;
    }
}
