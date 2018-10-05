<?php

namespace rollun\datahandler\Processor\Factory;

use Interop\Container\ContainerInterface;
use rollun\datahandler\Processor\Evaluation as EvaluationProcessor;
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
 *          EvaluationProcessorAbstractFactory::class => [
 *              'evaluationProcessorServiceName1' => [
 *                  'class' => FilterApplier::class,
 *                  'options' => [ // optional
 *                      'validator' => 'validatorServiceName1',
 *                      'expressionLanguage' => 'expressionLanguageServiceName'
 *                      //...
 *                  ],
 *              ],
 *              'evaluationProcessorServiceName2' => [
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
 *
 * </code>
 *
 * Class EvaluationProcessorAbstractFactory
 * @package rollun\datahandler\Processor\Factory
 */
class EvaluationProcessorAbstractFactory extends AbstractProcessorAbstractFactory
{
    /**
     * Default caused class
     */
    const DEFAULT_CLASS = EvaluationProcessor::class;

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
        // Service config from $container
        $serviceConfig = $this->getServiceConfig($container, $requestedName);

        $class = $this->getClass($serviceConfig);

        // Merged $options with $serviceConfig
        $pluginOptions = $this->getPluginOptions($serviceConfig, $options);

        $validator = $this->createValidator($container, $pluginOptions);

        $expressionLanguage = $this->getExpressionLanguage($container, $pluginOptions);

        // Remove options that are intended for the validator (extra options that no need in processor)
        $clearedPluginOptions = $this->clearPluginOptions($pluginOptions);

        return new $class($clearedPluginOptions, $validator, $expressionLanguage);
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
