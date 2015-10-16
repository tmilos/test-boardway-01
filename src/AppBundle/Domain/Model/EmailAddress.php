<?php

namespace AppBundle\Domain\Model;

use AppBundle\Domain\Error\DomainValueException;

class EmailAddress extends AbstractValue
{
    /** @var string */
    private $name;

    /** @var string */
    private $domain;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $matches = null;
        if (!preg_match('/^(.+)\@(\S+\.\S+)$/', $value, $matches)) {
             throw new DomainValueException(sprintf('Invalid email "%s"', $value));
        }

        parent::__construct($value);

        $this->name = $matches[1];
        $this->domain = $matches[2];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }
}
