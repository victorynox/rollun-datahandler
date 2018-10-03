<?php

namespace rollun\datahandler\Validator\Decorator\Factory;

use Interop\Container\ContainerInterface;
use rollun\datahandler\Validator\Decorator\Cached;

/**
 * Create and return instance of CachedValidator
 *
 * This Factory depends on Container (which should return an 'config' as array)
 *
 * Config example:
 * <code>
 * 'validators' => [
 *      'abstract_factory_config' => [
 *          CachedDecoratorAbstractFactory::class => [
 *              'cachedDecoratorServiceName1' => [
 *                  'class' => ArrayAdapter::class,
 *                  'options' => [
 *                      'validator' => 'validator-service', // required
 *                  ],
 *              ],
 *              'cachedDecoratorServiceName2' => [
 *                  //...
 *              ],
 *          ],
 *      ],
 *      'abstract_factories' => [
 *          //...
 *      ],
 *      'aliases' => [
 *          //...
 *      ],
 *      //...
 * ],
 * </code>
 *
 * Class CachedDecoratorAbstractFactory
 * @package rollun\datahandler\Validator\Factory
 */
class CachedDecoratorAbstractFactory extends AbstractValidatorDecoratorAbstractFactory
{
    /**
     * Parent class for plugin
     */
    const DEFAULT_CLASS = Cached::class;

    /**
     * Config for exception message
     */
    const EXCEPTION_MASSAGE_KEY = 'exceptionMassage';

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Cached
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Service config from $container
        $serviceConfig = $this->getServiceConfig($container, $requestedName);

        $class = $this->getClass($serviceConfig);

        // Merged $options with $serviceConfig
        $decoratorOptions = $this->getPluginOptions($serviceConfig, $options);

        $cachedValidator = $this->getDecoratedValidator($container, $decoratorOptions);

        return new $class($cachedValidator, $requestedName);
    }
}
