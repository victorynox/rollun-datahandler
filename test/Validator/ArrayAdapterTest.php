<?php

namespace rollun\test\datahandler\Validator;

use PHPUnit\Framework\TestCase;
use rollun\datahandler\Validator\ArrayAdapter;
use Zend\Validator\Digits;
use Zend\Validator\Exception\InvalidArgumentException;

class ArrayAdapterTest extends TestCase
{
    public function testPositiveOneColumnValid()
    {
        $validator = new Digits();
        $object = new ArrayAdapter($validator, [
            'columnsToValidate' => 'key1'
        ]);
        $isValid = $object->isValid([
            'key1' => '1234'
        ]);

        $this->assertTrue($isValid);
    }

    public function testNegativeOneColumnValid()
    {
        $validator = new Digits();
        $object = new ArrayAdapter($validator, [
            'columnsToValidate' => 'key1'
        ]);
        $isValid = $object->isValid([
            'key1' => 'abcd1234'
        ]);

        $this->assertFalse($isValid);
    }

    public function testPositiveSeveralColumnValid()
    {
        $validator = new Digits();
        $object = new ArrayAdapter($validator, [
            'columnsToValidate' => ['key1', 'key2', 'key3']
        ]);
        $isValid = $object->isValid([
            'key1' => '12345',
            'key2' => '45678',
            'key3' => '89012',
        ]);

        $this->assertTrue($isValid);
    }

    public function testNegativeSeveralColumnValid()
    {
        $validator = new Digits();
        $object = new ArrayAdapter($validator, [
            'columnsToValidate' => ['key1', 'key2', 'key3']
        ]);
        $isValid = $object->isValid([
            'key1' => '12345',
            'key2' => 'asdsf',
            'key3' => '89012',
        ]);

        $this->assertFalse($isValid);
    }

    public function testInvalidValueException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Incoming value must be an array');
        $validator = new Digits();
        $object = new ArrayAdapter($validator, [
            'columnsToValidate' => ['key1', 'key2', 'key3']
        ]);
        $object->isValid(null);
    }

    public function testInvalidColumnException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("INVALID_COLUMN doesn't exist in incoming value");
        $validator = new Digits();
        $object = new ArrayAdapter($validator, [
            'columnsToValidate' => ['key1', 'key2', 'INVALID_COLUMN']
        ]);
        $object->isValid([
            'key1' => '12345',
            'key2' => 'asdsf',
            'key3' => '89012',
        ]);
    }

    public function testInvalidColumnsToValidateOptions()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid option 'columnsToValidate'");
        $validator = new Digits();
        new ArrayAdapter($validator, [
            'columnsToValidate' => null
        ]);
    }

    public function testUnsetColumnsToWriteOption()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Missing 'columnsToValidate' option");
        $validator = new Digits();
        $object = new ArrayAdapter($validator);
        $object->isValid([
            'key1' => '12345',
            'key2' => 'asdsf',
            'key3' => '89012',
        ]);
    }
}
