<?php

namespace AppBundle\Domain\Command;

use AppBundle\Domain\Model\CompanyId;
use AppBundle\Domain\Model\EmailAddress;

class VerifyBusinessCommand
{
    /** @var EmailAddress */
    private $accountId;

    /** @var CompanyId */
    private $companyId;

    /**
     * @param EmailAddress $accountId
     * @param CompanyId $companyId
     */
    public function __construct(EmailAddress $accountId, CompanyId $companyId)
    {
        $this->accountId = $accountId;
        $this->companyId = $companyId;
    }

    /**
     * @return EmailAddress
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @return CompanyId
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }
}
