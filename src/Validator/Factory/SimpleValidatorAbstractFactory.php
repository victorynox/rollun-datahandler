<?php

namespace rollun\datanadler\Validator\Factory;

use rollun\datanadler\Factory\PluginAbstractFactoryAbstract;
use Interop\Container\ContainerInterface;
use Zend\Validator\ValidatorInterface;

/**
 * Config example
 *
 * 'validators' => [
 *      'abstract_factory_config' => [
 *          IsCountable::class => [
 *              'requestedName' => [
 *                  'class' => IsCountable::class,
 *                  'options' => [],
 *              ],
 *          ],
 *      ],
 * ],
 *
 * Class SimpleValidatorAbstractFactory
 * @package rollun\datanadler\Validator\Factory
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
    const KEY = 'filters';

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig = $this->getServiceConfig($container, $requestedName);
        $options = $this->getPluginOptions($serviceConfig, $options);
        $class = $this->getClass($serviceConfig);

        return new $class($options);
    }
}
