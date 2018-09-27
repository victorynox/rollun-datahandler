<?php

namespace rollun\test\datahandler\Processor;

use PHPUnit\Framework\TestCase;
use rollun\datahandler\Processor\Evaluation;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Zend\ServiceManager\ServiceManager;

class EvaluationTest extends TestCase
{
    public function test()
    {
        $expressionLanguage = new ExpressionLanguage();
        $container = new ServiceManager();
        $container->setService('filter1', function ($value) {
            return $value . '!';
        });
        $container->setService('filter2', function ($value) {
            return $value . '!!!!!';
        });

        $serviceName = 'filter1';
        $function = new ExpressionFunction($serviceName, function ($value) use ($serviceName) {
            return sprintf(
                '$container = require \'config/container.php\';' .
                '$callable = $container->get(\'%2$s\');' .
                '($callable(%2$s)', $serviceName, $value);
        }, function ($arguments, $service, $value) use ($container) {
            $callable = $container->get($service);
            return $callable($value);
        });


        $expressionLanguage->addFunction($function);
        $object = new Evaluation(
            [
                'expression' => 'filter1(a)',
                'resultColumn' => 'c',
            ],
            null,
            $expressionLanguage
        );

        $value = $object->doProcess([
            'a' => 6,
            'b' => 4,
        ]);

        $this->assertEquals($value['c'], '6!!!!!');
    }
}