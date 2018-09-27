<?php

namespace rollun\datahandler\Evaluator;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Zend\ServiceManager\AbstractPluginManager;

/**
 * Class ExpressionEvaluator
 * @package rollun\datahandler\Evaluator
 */
class ExpressionEvaluator extends ExpressionLanguage
{
    /**
     * @param AbstractPluginManager $pluginManager
     * @param array $pluginFunctions
     * @param string $calledMethod
     */
    public function registerPluginFunctions(
        AbstractPluginManager $pluginManager,
        array $pluginFunctions,
        string $calledMethod
    ) {
        foreach ($pluginFunctions as $filterService) {
            $compiler = function ($value) use ($filterService, $pluginManager, $calledMethod) {
                $evaluate =
                    '$container = require \'config/container.php\';' .
                    '$filterPluginManager = $container->get(\'%s\');' .
                    '$plugin = $pluginManager->get(\'%s\');' .
                    '($plugin->%s(%s))';

                return sprintf($evaluate, $pluginManager, $filterService, $calledMethod, $value);
            };

            $evaluator = function ($arguments, $value) use ($filterService, $pluginManager, $calledMethod) {
                $plugin = $pluginManager->get($filterService);
                return $plugin->{$calledMethod}($value);
            };

            $this->addFunction(new ExpressionFunction($filterService, $compiler, $evaluator));
        }
    }
}
