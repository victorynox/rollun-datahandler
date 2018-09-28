<?php

namespace rollun\datahandler\Evaluator\ExpressionFunctionProviders;

use LogicException;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Zend\ServiceManager\AbstractPluginManager;

class Plugin implements ExpressionFunctionProviderInterface
{
    protected $pluginManager;

    protected $pluginServices;

    protected $calledMethod;

    public function __construct(AbstractPluginManager $pluginManager, array $pluginServices, string $calledMethod)
    {
        $this->pluginManager = $pluginManager;
        $this->pluginServices = $pluginServices;
        $this->calledMethod = $calledMethod;
    }

    public function getFunctions()
    {
        $functionExpressions = [];

        foreach ($this->pluginServices as $pluginService) {
            $pluginManager = $this->pluginManager;
            $calledMethod = $this->calledMethod;

            $compiler = function ($value) use (
                $pluginService,
                $pluginManager,
                $calledMethod
            ) {
                throw new LogicException("Evaluator for $pluginService doesn't exist");
            };

            $evaluator = function ($arguments, $value) use (
                $pluginManager,
                $pluginManager,
                $calledMethod
            ) {
                $plugin = $pluginManager->get($pluginManager);
                return $plugin->{$calledMethod}($value);
            };

            $functionExpressions[] = new ExpressionFunction($pluginManager, $compiler, $evaluator);
        }

        return $functionExpressions;
    }
}
