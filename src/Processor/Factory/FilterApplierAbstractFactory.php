<?php

namespace rollun\datanadler\Processor\Factory;

use rollun\datanadler\Processor\FilterApplier;
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
 *                  'options' => [],
 *                  'validator' => 'validator-service',
 *              ],
 *          ],
 *      ],
 * ],
 *
 * Class FilterApplierAbstractFactory
 * @package rollun\datanadler\Processor\Factory
 */
class FilterApplierAbstractFactory extends SimpleProcessorAbstractFactory
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
        $validator = $this->getValidator($container, $serviceConfig, $options);
        $processorOptions = $this->getPluginOptions($serviceConfig, $options);
        $class = $this->getClass($serviceConfig);
        $filterPluginManager = $container->get(FilterPluginManager::class);

        return new $class($filterPluginManager, $processorOptions, $validator);
    }
}
