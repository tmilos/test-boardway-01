<?php

namespace AppBundle\Domain\Model;

class EncodedPassword extends AbstractValue
{
    /** @var string */
    private $salt;

    /**
     * @param string $encodedPassword
     * @param string $salt
     */
    public function __construct($encodedPassword, $salt)
    {
        parent::__construct($encodedPassword);

        $this->salt = $salt;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }
}
