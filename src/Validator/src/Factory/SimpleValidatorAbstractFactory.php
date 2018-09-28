<?php

namespace rollun\datahandler\Validator\Factory;

use rollun\datahandler\Factory\PluginAbstractFactoryAbstract;
use Interop\Container\ContainerInterface;
use Zend\Validator\ValidatorInterface;

/**
 * Create and return instance of ValidatorInterface
 *
 * This Factory depends on Container (which should return an 'config' as array)
 *
 * Config example:
 * <code>
 * 'validators' => [
 *      'abstract_factory_config' => [
 *          SimpleValidatorAbstractFactory::class => [
 *              'simpleValidatorServiceName1' => [
 *                  'class' => IsCountable::class,
 *                  'options' => [ // by default is not required
 *                      // other options, specific for each validator
 *                      //...
 *                  ],
 *              ],
 *              'simpleValidatorServiceName2' => [
 *                  //...
 *              ],
 *          ],
 *      ],
 * ],
 * </code>
 *
 * Class SimpleValidatorAbstractFactory
 * @package rollun\datahandler\Validator\Factory
 */
class SimpleValidatorAbstractFactory extends PluginAbstractFactoryAbstract
{
    /**
     * Parent class for plugin. By default doesn't set
     */
    const DEFAULT_CLASS = ValidatorInterface::class;

    /**
     * Common namespace name for plugin config. By default doesn't set
     */
    const KEY = 'validators';

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ValidatorInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig = $this->getServiceConfig($container, $requestedName);
        $pluginOptions = $this->getPluginOptions($serviceConfig, $options);
        $class = $this->getClass($serviceConfig, true);

        return new $class($pluginOptions);
    }
}
