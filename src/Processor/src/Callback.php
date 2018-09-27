<?php

namespace rollun\datahandler\Processor;

class Callback
{
    public function __invoke()
    {
        return pow(1, 2);
    }
}
