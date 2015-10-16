<?php

namespace AppBundle\Domain\Model;

use AppBundle\Domain\Error\DomainValueException;

abstract class AbstractEnum extends AbstractValue implements EnumInterface
{
    public function __construct($value)
    {
        if (false === static::isValid($value)) {
            throw new DomainValueException('Invalid value');
        }

        parent::__construct($value);
    }

    /**
     * @param string|int $value
     *
     * @return bool
     */
    public static function isValid($value)
    {
        $all = static::all();

        return isset($all[$value]);
    }
}
