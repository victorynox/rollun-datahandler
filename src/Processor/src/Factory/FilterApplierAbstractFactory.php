<?php

namespace rollun\datahandler\Processor\Factory;

use rollun\datahandler\Processor\FilterApplier;
use Interop\Container\ContainerInterface;
use Zend\Filter\FilterPluginManager;

/**
 * Config example
 *
 * 'processors' => [
 *      'abstract_factory_config' => [
 *          FilterApplierAbstractFactory::class => [
 *              'requestedName' => [
 *                  'class' => FilterApplier::class,
 *                  'options' => [
 *                      'validator' => 'validator-service',
 *                      'validatorOptions' => [],
 *                      // other options
 *                  ],
 *              ],
 *          ],
 *      ],
 * ],
 *
 * Class FilterApplierAbstractFactory
 * @package rollun\datahandler\Processor\Factory
 */
class FilterApplierAbstractFactory extends ProcessorAbstractFactoryAbstract
{
    /**
     * Default class for filter applier processor
     */
    const DEFAULT_CLASS = FilterApplier::class;

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig = $this->getServiceConfig($container, $requestedName);
        $pluginOptions = $this->getPluginOptions($serviceConfig, $options);
        $validator = $this->getValidator($container, $pluginOptions);
        $clearedPluginOptions = $this->clearPluginOptions($pluginOptions);

        $class = $this->getClass($serviceConfig);
        $filterPluginManager = $container->get(FilterPluginManager::class);

        return new $class($clearedPluginOptions, $validator, $filterPluginManager);
    }
}
