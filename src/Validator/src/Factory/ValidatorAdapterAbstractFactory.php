<?php

namespace rollun\datahandler\Validator\Factory;

use rollun\datahandler\Factory\PluginAbstractFactoryAbstract;
use rollun\datahandler\Validator\ArrayAdapter;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\Validator\ValidatorPluginManager;

/**
 * Create and return instance of ArrayAdapter
 *
 * This Factory depends on Container (which should return an 'config' as array)
 *
 * Config example:
 * <code>
 * self::class => [
 *      'abstract_factory_config' => [
 *          ValidatorAdapterAbstractFactory::class => [
 *              'validatorAdapterServiceName1' => [
 *                  'class' => ArrayAdapter::class,
 *                  'options' => [
 *                      'validator' => 'validator-service', // required
 *                      'validatorOptions' => [], // validator options, by default is not required
 *                      //...
 *                  ],
 *              ],
 *              'validatorAdapterServiceName2' => [
 *                  //...
 *              ],
 *          ],
 *      ],
 * ],
 * </code>
 *
 * Class AdapterValidatorAbstractFactory
 * @package rollun\datahandler\Validator\Factory
 */
class ValidatorAdapterAbstractFactory extends PluginAbstractFactoryAbstract implements AbstractFactoryInterface
{
    /**
     * Parent class for plugin. By default doesn't set
     */
    const DEFAULT_CLASS = ArrayAdapter::class;

    /**
     * Common namespace name for plugin config. By default doesn't set
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
     * @param string $requestedName
     * @param array|null $options
     * @return ArrayAdapter
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig = $this->getServiceConfig($container, $requestedName);
        $pluginOptions = $this->getPluginOptions($serviceConfig, $options);
        $class = $this->getClass($serviceConfig);
        $decoratedValidator = $this->getDecoratedValidator($container, $pluginOptions);

        $clearedPluginOptions = $this->clearPluginOptions($pluginOptions);

        return new $class($decoratedValidator, $clearedPluginOptions);
    }

    /**
     * @param ContainerInterface $container
     * @param array $pluginOptions
     * @return null
     */
    public function getDecoratedValidator(ContainerInterface $container, array $pluginOptions)
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