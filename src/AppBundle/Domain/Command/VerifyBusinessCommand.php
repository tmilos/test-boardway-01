<?php

namespace AppBundle\Domain\Command;

use AppBundle\Domain\Model\AccountId;
use AppBundle\Domain\Model\CompanyId;

class VerifyBusinessCommand
{
    /** @var AccountId */
    private $accountId;

    /** @var CompanyId */
    private $companyId;

    /**
     * @param AccountId $accountId
     * @param CompanyId $companyId
     */
    public function __construct(AccountId $accountId, CompanyId $companyId)
    {
        $this->accountId = $accountId;
        $this->companyId = $companyId;
    }

    /**
     * @return AccountId
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
