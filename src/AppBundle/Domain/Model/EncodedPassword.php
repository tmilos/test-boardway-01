<?php

namespace AppBundle\Domain\Model;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

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
     * @param PasswordEncoderInterface $encoder
     * @param string                  $plainPassword
     *
     * @return EncodedPassword
     */
    public static function encode(PasswordEncoderInterface $encoder, $plainPassword)
    {
        $salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $encodedPassword = $encoder->encodePassword($plainPassword, $salt);

        return new EncodedPassword($encodedPassword, $salt);
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }
}
