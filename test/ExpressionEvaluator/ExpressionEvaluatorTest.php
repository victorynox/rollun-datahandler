<?php

namespace rollun\test\datahandler\ExpressionEvaluator;

use PHPUnit\Framework\TestCase;
use rollun\datahandler\Evaluator\ExpressionEvaluator;
use Zend\Filter\FilterPluginManager;
use Zend\ServiceManager\ServiceManager;

class ExpressionEvaluatorTest extends TestCase
{
    /**
     * @var ExpressionEvaluator
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new ExpressionEvaluator();
    }

    public function testAddFilterFunctions()
    {
        $pluginManager = new FilterPluginManager(new ServiceManager());
        $pluginFunctions = ['stringTrim', 'digits'];
        $calledMethod = 'filter';

        $this->object->registerPluginFunctions($pluginManager, $pluginFunctions, $calledMethod);
        $evaluated1 = $this->object->evaluate('stringTrim(\'  abcd   \')');
        $evaluated2 = $this->object->evaluate('digits(\'  abcd   \')');

        $this->assertEquals('abcd', $evaluated1);
        $this->assertEquals('', $evaluated2);
    }
}
