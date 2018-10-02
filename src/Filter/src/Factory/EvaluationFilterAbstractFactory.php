<?php

namespace rollun\datahandler\Filter\Factory;

use Interop\Container\ContainerInterface;
use rollun\datahandler\Factory\PluginAbstractFactoryAbstract;
use rollun\datahandler\Filter\Evaluation;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * Create and return instance of Evaluation
 *
 * This Factory depends on Container (which should return an 'config' as array)
 *
 * Config example:
 * <code>
 * 'filters' => [
 *      'abstract_factory_config' => [
 *          SimpleFilterAbstractFactory::class => [
 *              'simpleFilterServiceName1' => [
 *                  'class' => stringTrim::class,
 *                  'options' => [ // by default is not required
 *                      'expressionLanguage' => 'expressionLanguageServiceName'
 *                      // filter options, specific for each filter
 *                      //...
 *                  ],
 *              ],
 *              'simpleFilterServiceName2' => [
 *                  //...
 *              ],
 *          ],
 *      ],
 * ],
 * </code>
 *
 * Class SimpleFilterAbstractFactory
 * @package rollun\datahandler\Filter\Factory
 */
class EvaluationFilterAbstractFactory extends PluginAbstractFactoryAbstract
{
    /**
     * Common namespace name for plugin config. By default doesn't set
     */
    const KEY = 'filters';

    /**
     * Parent class for plugin
     */
    const DEFAULT_CLASS = Evaluation::class;

    /**
     * Config key for expression language service
     */
    const EXPRESSION_LANGUAGE_KEY = 'expressionLanguage';

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig = $this->getServiceConfig($container, $requestedName);

        /** @var Evaluation $class */
        $class = $this->getClass($serviceConfig);
        $pluginOptions = $this->getPluginOptions($serviceConfig, $options);
        $expressionLanguage = $this->getExpressionLanguage($container, $pluginOptions);
        $pluginOptions = $this->clearPluginOptions($pluginOptions);

        return new $class($pluginOptions, $expressionLanguage);
    }

    /**
     * @param ContainerInterface $container
     * @param $pluginOptions
     * @return ExpressionLanguage|null
     */
    public function getExpressionLanguage(ContainerInterface $container, $pluginOptions)
    {
        $expressionLanguage = null;

        if (!isset($pluginOptions[self::EXPRESSION_LANGUAGE_KEY])) {
            return $expressionLanguage;
        }

        return $container->get($pluginOptions[self::EXPRESSION_LANGUAGE_KEY]);
    }

    protected function clearPluginOptions(array $pluginOptions)
    {
        unset($pluginOptions[self::EXPRESSION_LANGUAGE_KEY]);

        return $pluginOptions;
    }
}
