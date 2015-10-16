<?php

namespace AppBundle\Domain\Model;

use AppBundle\Domain\Error\DomainValueException;
use Broadway\EventSourcing\EventSourcedEntity;

abstract class AbstractValue extends EventSourcedEntity
{
    /** @var int|string */
    protected $value;

    /**
     * @param string|int $value
     */
    public function __construct($value)
    {
        if (false === is_scalar($value)) {
            throw new DomainValueException('Only scalars can be value types');
        }

        $this->value = $value;
    }

    /**
     * @return string|int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param AbstractValue $other
     *
     * @return bool
     */
    public function equal(AbstractValue $other)
    {
        return $this->getValue() == $other->getValue();
    }

    /**
     * @param AbstractValue $other
     *
     * @return bool
     */
    public function notEqual(AbstractValue $other)
    {
        return $this->getValue() != $other->getValue();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }
}
