<?php

namespace rollun\test\utils\Cleaner\Example\DirCleaner;

use rollun\utils\Cleaner\Example\DirCleaner\CleanRuner;
use rollun\utils\Cleaner\CleaningValidator\CallableValidator;
use rollun\utils\Cleaner\CleaningValidator\ZendValidatorAdapter;
use Zend\Validator\File\Size as ZendValidatorFileSize;

class DirCleanerTest extends \PHPUnit\Framework\TestCase
{

    /**
     *
     * @var CleanRuner
     */
    protected $object;

    /**
     *
     * @var int
     */
    protected $maxSizeInBytes;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new CleanRuner;
        //5 files with size 1 ,3 ,5 ,7 and 9 bytes have made in folder 'data/cleanIt'
        $this->assertFileExists($this->object->fullPath . "1.txt");
        $this->assertFileExists($this->object->fullPath . "9.txt");
        $this->maxSizeInBytes = 3;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        //max file size - 3 bytes
        //only 1.txt and 3.txt now exist
        $this->assertFileExists($this->object->fullPath . "1.txt");
        $this->assertFileExists($this->object->fullPath . "3.txt");
        $this->assertFileNotExists($this->object->fullPath . "7.txt");
    }

    public function test_DirCleaner_ZendValidator()
    {

        //make Zend file size validator
        $zendFileValidator = new ZendValidatorFileSize($this->maxSizeInBytes);
        //make CleaningValidatorInterface from ZendValidatorInterface
        $cleaningValidator = new ZendValidatorAdapter($zendFileValidator);

        $this->object->run($cleaningValidator);
    }

    public function test_DirCleaner_CallableValidator()
    {

        $callable = function ($filename) {
            return filesize($filename) <= $this->maxSizeInBytes;
        };
        //make CallableValidator from function
        $callableValidator = new CallableValidator($callable);
        $this->object->run($callableValidator);
    }

}
