<?php

namespace rollun\utils\Cleaner\Example\DirCleaner;

use rollun\utils\Cleaner\Cleaner;
use rollun\utils\Cleaner\Example\DirCleaner\FilesList;
use rollun\utils\Cleaner\CleaningValidator\ZendValidatorAdapter;
use Zend\Validator\File\Size as ZendValidatorFileSize;
use rollun\utils\Cleaner\CleaningValidator\CleaningValidatorInterface;

/**
 *
 * To run example add to index.php next code
 *
 * <code>
 *  use rollun\utils\Cleaner\Example\DirCleaner\CleanRuner;
 *
 *  $cleanRuner = new CleanRuner;
 *  //5 files with size 1 ,3 ,5 ,7 and 9 bytes have made in folder 'data/cleanIt'
 *
 *  $cleanRuner->deleteBigFiles(4); //max file size - 4bytes
 *  //only 1.txt and 3.txt now exist
 *
 *  exit();
 * </code>
 */
class CleanRuner
{

    const CLEANED_DIR_NAME = 'cleanIt';

    public $fullPath;

    public function __construct()
    {
        $this->fullPath = \rollun\installer\Command::getDataDir()
                . static::CLEANED_DIR_NAME
                . DIRECTORY_SEPARATOR;

        //make dir and delete all files
        if (file_exists($this->fullPath)) {
            //This will delete all files in a directory matching a pattern in one line of code.
            array_map('unlink', glob($this->fullPath . "*.txt"));
        } else {
            mkdir($this->fullPath, 0777, true);
        }

        //Make 5 files with size 1,3,5,7,9 bytes
        $this->makeFilesWithDifferentSize();
    }

    public function deleteBigFiles($maxSizeInBytes)
    {
        //make Zend file size validator
        $zendFileValidator = new ZendValidatorFileSize($maxSizeInBytes);
        //make CleaningValidatorInterface from ZendValidatorInterface
        $cleaningValidator = new ZendValidatorAdapter($zendFileValidator);

        $this->run($cleaningValidator);
    }

    public function run(CleaningValidatorInterface $cleaningValidator)
    {
        $cleanableList = new FilesList($this->fullPath);
        $cleaner = new Cleaner($cleanableList, $cleaningValidator);
        $cleaner->cleanList();
    }

    /**
     * Make 5 files with size 1,3,5,7,9 bytes
     */
    private function makeFilesWithDifferentSize()
    {
        foreach ([1, 3, 5, 7, 9] as $value) {
            $filename = $this->fullPath . $value . ".txt";
            file_put_contents($filename, str_repeat("a", $value));
        }
    }

}
