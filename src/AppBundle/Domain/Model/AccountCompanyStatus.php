<?php

namespace AppBundle\Domain\Model;

use AppBundle\Domain\Error\DomainOperationException;
use AppBundle\Domain\Event\AccountCompanyRoleAdded;

class AccountCompanyStatus extends AbstractEnum
{
    const NONE = 'none';
    const PENDING = 'pending';
    const ACTIVE = 'active';

    /** @var EmailAddress */
    private $accountId;

    /** @var CompanyId|null */
    private $companyId;

    /** @var CompanyRole[] */
    private $companyRoles = [];

    /**
     * @param EmailAddress $accountId
     * @param string       $value
     */
    public function __construct(EmailAddress $accountId, $value)
    {
        parent::__construct($value);

        $this->accountId = $accountId;
    }

    /**
     * value => title
     *
     * @return array
     */
    public static function all()
    {
        return [
            self::NONE => 'account_company_status.none',
            self::PENDING => 'account_company_status.pending',
            self::ACTIVE => 'account_company_status.active',
        ];
    }

    /**
     * @param EmailAddress $accountId
     *
     * @return AccountCompanyStatus
     */
    public static function none(EmailAddress $accountId)
    {
        return new AccountCompanyStatus($accountId, self::NONE);
    }

    /**
     * @return AccountCompanyStatus
     */
    public static function pending(EmailAddress $accountId)
    {
        return new AccountCompanyStatus($accountId, self::PENDING);
    }

    /**
     * @param EmailAddress $accountId
     * @param CompanyId    $companyId
     *
     * @return AccountCompanyStatus
     */
    public static function active(EmailAddress $accountId, CompanyId $companyId)
    {
        $status = new AccountCompanyStatus($accountId, self::ACTIVE);
        $status->companyId = $companyId;

        return $status;
    }

    /**
     * @return CompanyId|null
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * @param CompanyRole $role
     *
     * @return AccountCompanyStatus
     */
    public function addCompanyRole(CompanyRole $role)
    {
        if ($this->value != self::ACTIVE) {
            throw new DomainOperationException('Company role can be set only in active status');
        }

        if (false === isset($this->companyRoles[$role->getValue()])) {
            $this->companyRoles[$role->getValue()] = $role;
            $this->apply(new AccountCompanyRoleAdded($this->accountId, $role));
        }

        return $this;
    }
}
