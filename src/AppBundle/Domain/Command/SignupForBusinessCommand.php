<?php

namespace AppBundle\Domain\Command;

use AppBundle\Domain\Model\AccountId;
use AppBundle\Domain\Model\EmailAddress;
use AppBundle\Domain\Model\EncodedPassword;

class SignupForBusinessCommand
{
    /** @var EmailAddress */
    private $emailAddress;

    /** @var EncodedPassword */
    private $encodedPassword;

    /**
     * @param EmailAddress    $emailAddress
     * @param EncodedPassword $encodedPassword
     */
    public function __construct(EmailAddress $emailAddress, EncodedPassword $encodedPassword)
    {
        $this->emailAddress = $emailAddress;
        $this->encodedPassword = $encodedPassword;
    }

    /**
     * @return EmailAddress
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @return EncodedPassword
     */
    public function getEncodedPassword()
    {
        return $this->encodedPassword;
    }
}
