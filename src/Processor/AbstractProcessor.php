<?php

namespace rollun\datanadler\Processor;

use Traversable;
use Zend\Validator\ValidatorInterface;

/**
 * Class AbstractProcessor
 * @package rollun\datanadler\Processor
 */
abstract class AbstractProcessor implements ProcessorInterface
{
    /**
     * Validator check necessity of process
     * If validator return true, we can do process
     *
     * @var ValidatorInterface
     */
    protected $validator = null;

    /**
     * Filter options
     *
     * @var array
     */
    protected $options = [];

    /**
     * AbstractProcessor constructor.
     * @param array $options
     * @param ValidatorInterface|null $validator
     */
    public function __construct($options = [], ValidatorInterface $validator = null)
    {
        $this->setOptions($options);
        $this->validator = $validator;
    }

    /**
     * @param  array|Traversable $options
     * @return self
     * @throws \InvalidArgumentException
     */
    public function setOptions($options)
    {
        if (!is_array($options) && !$options instanceof Traversable) {
            throw new \InvalidArgumentException(sprintf(
                '"%s" expects an array or Traversable; received "%s"',
                __METHOD__,
                (is_object($options) ? get_class($options) : gettype($options))
            ));
        }

        foreach ($options as $key => $value) {
            $setter = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
            if (method_exists($this, $setter)) {
                $this->{$setter}($value);
            } elseif (array_key_exists($key, $this->options)) {
                $this->options[$key] = $value;
            } else {
                throw new \InvalidArgumentException(
                    sprintf(
                        'The option "%s" does not have a matching %s setter method or options[%s] array key',
                        $key,
                        $setter,
                        $key
                    )
                );
            }
        }

        return $this;
    }

    /**
     * @param array $value
     * @return array
     */
    final public function process(array $value): array
    {
        if (isset($this->validator)) {
            $isValid = $this->validator->isValid($value);

            if (!$isValid) {
                return $value;
            }
        }

        $value = $this->doProcess($value);

        return $value;
    }

    /**
     * Main process job
     *
     * @param $value
     * @return array
     */
    abstract protected function doProcess(array $value);

    /**
     * @param $validator
     */
    protected function setValidator($validator)
    {
        $this->validator = $validator;
    }

    /**
     * Retrieve options representing object state
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
