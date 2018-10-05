<?php

namespace rollun\utils\Cleaner\CleaningValidator;

use Opis\Closure\SerializableClosure;
use rollun\utils\Cleaner\CleaningValidator\CleaningValidatorInterface;

class CallableValidator implements CleaningValidatorInterface
{

    /**
     * @var callable
     */
    protected $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function isValid($value): bool
    {
        return call_user_func($this->callable, $value);
    }


    public function __sleep()
    {
        if(is_array($this->callable)) {
            $this->callable = \Closure::fromCallable($this->callable);
        }
        if($this->callable instanceof \Closure) {
            $this->callable = new SerializableClosure($this->callable);
        }
        return ["callable"];
    }

    public function __wakeup()
    {
        $callback = $this->callable;
        if (!is_callable($callback, true)) {
            throw new \RuntimeException(
                'There is not correct instance callable in Callback'
            );
        }
    }
}
