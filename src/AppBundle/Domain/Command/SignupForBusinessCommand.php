<?php

namespace AppBundle\Domain\Command;

use AppBundle\Domain\Model\AccountId;
use AppBundle\Domain\Model\EmailAddress;
use AppBundle\Domain\Model\EncodedPassword;

class SignupForBusinessCommand
{
    /** @var AccountId */
    private $accountId;

    /** @var EmailAddress */
    private $emailAddress;

    /** @var EncodedPassword */
    private $encodedPassword;

    /**
     * @param AccountId       $accountId
     * @param EmailAddress    $emailAddress
     * @param EncodedPassword $encodedPassword
     */
    public function __construct(AccountId $accountId, EmailAddress $emailAddress, EncodedPassword $encodedPassword)
    {
        $this->accountId = $accountId;
        $this->emailAddress = $emailAddress;
        $this->encodedPassword = $encodedPassword;
    }

    /**
     * @return AccountId
     */
    public function getAccountId()
    {
        return $this->accountId;
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
