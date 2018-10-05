<?php

namespace rollun\test\datahandler\Validator\Decorator;

use PHPUnit\Framework\TestCase;
use rollun\datahandler\Validator\Decorator\Cached;
use Zend\Validator\Digits;
use Zend\Validator\Ip;

/**
 * Class CachedTest
 * @package rollun\test\datahandler\Validator\Decorator
 */
class CachedTest extends TestCase
{
    public function testSameValidators()
    {
        $cachedDecorator1 = new Cached(new Digits(), 'cachedDigits');
        $cachedDecorator2 = new Cached(new Ip(), 'cachedDigits');

        $this->assertTrue($cachedDecorator1->getCachedValidator() === $cachedDecorator2->getCachedValidator());
    }

    public function testDifferentValidators()
    {
        $validator = new Digits();
        $cachedDecorator1 = new Cached(clone $validator, 'cachedDigits1');
        $cachedDecorator2 = new Cached(clone $validator, 'cachedDigits2');

        $this->assertFalse($cachedDecorator1->getCachedValidator() === $cachedDecorator2->getCachedValidator());
    }
}
