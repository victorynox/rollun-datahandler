<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 27.10.17
 * Time: 10:52
 */

namespace rollun\utils;

class IdGenerator
{
    /**
     * @var string
     */
    protected $idCharSet = "QWERTYUIOPASDFGHJKLZXCVBNM0123456789";

    /**
     * @var integer
     */
    protected $length;

    /**
     * IdGenerator constructor.
     * @param $length
     * @param string $idCharSet
     */
    public function __construct($length, $idCharSet = "QWERTYUIOPASDFGHJKLZXCVBNM0123456789")
    {
        $this->length = $length;
        $this->idCharSet = $idCharSet;
    }

    /**
     * @param $idCharSet
     */
    public function setIdCharSet($idCharSet)
    {
        $this->idCharSet = $idCharSet;
    }

    /**
     * @param $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * Generate id.
     * Generates an arbitrary length string of cryptographic random
     * @return array|string
     */
    public function generate()
    {
        $id = [];
        $idCharSetArray = str_split($this->idCharSet);
        $charArrayCount = count($idCharSetArray) - 1;
        for ($i = 0; $i < $this->length; $i++) {
            $id[$i] = $idCharSetArray[random_int(0, $charArrayCount)];
        }
        $id = implode("", $id);
        return $id;
    }
}
