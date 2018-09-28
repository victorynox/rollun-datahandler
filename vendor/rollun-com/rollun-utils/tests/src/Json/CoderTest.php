<?php

namespace rollun\test\utils\Php;

use rollun\utils\Json\Coder as JsonCoder;
use rollun\utils\Json\Exception as JsonException;
use rollun\test\utils\Json\SerializerTestAbstract;

class CoderTest extends SerializerTestAbstract
{

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed
     */
    protected function setUp()
    {
        $this->encoder = function ($value) {
            return call_user_func([JsonCoder::class, 'jsonEncode'], $value);
        };
        $this->decoder = function ($value) {
            return call_user_func([JsonCoder::class, 'jsonDecode'], $value);
        };
    }

    //==========================================================================

    /**
     * @dataProvider provider_ScalarType
     */
    public function testSerialize_ScalarType($in, $jsonString, $out = null)
    {
        parent::serialize($in, $jsonString, $out);
    }

    /**
     * @dataProvider provider_StringType
     */
    public function testSerialize_StringType($in, $jsonString, $out = null)
    {
        parent::serialize($in, $jsonString, $out);
    }

    /**
     * @dataProvider provider_ArrayType
     */
    public function testSerialize_ArrayType($in, $jsonString, $out = null)
    {
        parent::serialize($in, $jsonString, $out);
    }

    /**
     * @dataProvider provider_ObjectType
     */
    public function testSerialize_ObjectType($in, $jsonString, $out = null)
    {
        $this->expectException(JsonException::class);
        parent::serialize($in, $jsonString, $out);
    }

    /**
     * @dataProvider provider_ClosureType
     */
    public function testSerialize_ClosureType($in, $jsonString, $out = null)
    {
        $this->expectException(JsonException::class);
        parent::serialize($in, $jsonString, $out);
    }

    /**
     * @dataProvider provider_ResourceType
     */
    public function testSerialize_ResourceType($in, $jsonString, $out = null)
    {
        $this->expectException(JsonException::class);
        parent::serialize($in, $jsonString, $out);
    }

}
