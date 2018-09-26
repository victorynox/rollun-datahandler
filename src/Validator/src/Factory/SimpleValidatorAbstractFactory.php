<?php

namespace rollun\datahandler\Validator\Factory;

use rollun\datahandler\Factory\PluginAbstractFactoryAbstract;
use Interop\Container\ContainerInterface;
use Zend\Validator\ValidatorInterface;

/**
 * Config example
 *
 * 'validators' => [
 *      'abstract_factory_config' => [
 *          SimpleValidatorAbstractFactory::class => [
 *              'requestedName' => [
 *                  'class' => IsCountable::class,
 *                  'options' => [
 *                      // other options
 *                  ],
 *              ],
 *          ],
 *      ],
 * ],
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

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig = $this->getServiceConfig($container, $requestedName);
        $pluginOptions = $this->getPluginOptions($serviceConfig, $options);
        $class = $this->getClass($serviceConfig);

        return new $class($pluginOptions);
    }
}
