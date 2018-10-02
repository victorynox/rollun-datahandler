<?php

namespace rollun\datahandler\Processor\Factory;

use Interop\Container\ContainerInterface;
use rollun\datahandler\Processor\Evaluation;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * Create and return instance of Evaluation processor
 *
 * This Factory depends on Container (which should return an 'config' as array)
 *
 * Config example
 * <code>
 * 'processors' => [
 *      'abstract_factory_config' => [
 *          EvaluationAbstractProcessorFactory::class => [
 *              'evaluationProcessorServiceName1' => [
 *                  'class' => FilterApplier::class,
 *                  'options' => [ // by default is not required
 *                      'validator' => 'validator-service',
 *                      'validatorOptions' => [],
 *                      'expressionLanguage' => 'expressionLanguageServiceName'
 *                      // other options, specific for each processor
 *                      //...
 *                  ],
 *              ],
 *              'evaluationProcessorServiceName2' => [
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
class EvaluationProcessorAbstractFactory extends AbstractProcessorAbstractFactory
{
    /**
     * Parent class for plugin
     */
    const DEFAULT_CLASS = Evaluation::class;

    /**
     * Config key for expression language service
     */
    const EXPRESSION_LANGUAGE_KEY = 'expressionLanguage';

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ExpressionLanguage
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig = $this->getServiceConfig($container, $requestedName);

        /** @var Evaluation $class */
        $class = $this->getClass($serviceConfig);
        $pluginOptions = $this->getPluginOptions($serviceConfig, $options);
        $validator = $this->getValidator($container, $pluginOptions);
        $expressionLanguage = $this->getExpressionLanguage($container, $pluginOptions);
        $pluginOptions = $this->clearPluginOptions($pluginOptions);

        return new $class($pluginOptions, $validator, $expressionLanguage);
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
        $pluginOptions = parent::clearPluginOptions($pluginOptions);
        unset($pluginOptions[self::EXPRESSION_LANGUAGE_KEY]);

        return $pluginOptions;
    }
}
