<?php

namespace rollun\datahandler\Processor\Factory;

use rollun\datahandler\Processor\FilterApplier;
use Interop\Container\ContainerInterface;
use Zend\Filter\FilterPluginManager;

/**
 * Create and return instance of FilterApplier processor
 *
 * This Factory depends on Container (which should return an 'config' as array)
 *
 * Config example
 * <code>
 * 'processors' => [
 *      'abstract_factory_config' => [
 *          FilterApplierAbstractFactory::class => [
 *              'filterApplierProcessorServiceName1' => [
 *                  'class' => FilterApplier::class,
 *                  'options' => [ // by default is not required
 *                      'validator' => 'validator-service',
 *                      'validatorOptions' => [],
 *                      // other options, specific for each processor
 *                      //...
 *                  ],
 *              ],
 *              'filterApplierProcessorServiceName2' => [
 *                  //...
 *              ],
 *          ],
 *      ],
 * ],
 *
 * </code>
 *
 * Class FilterApplierAbstractFactory
 * @package rollun\datahandler\Processor\Factory
 */
class FilterApplierProcessorAbstractFactory extends AbstractProcessorAbstractFactory
{
    /**
     * Default class for filter applier processor
     */
    const DEFAULT_CLASS = FilterApplier::class;

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return FilterApplier
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
