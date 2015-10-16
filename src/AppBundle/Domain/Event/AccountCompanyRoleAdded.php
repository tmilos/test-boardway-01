<?php

namespace AppBundle\Domain\Event;

use AppBundle\Domain\Model\AccountId;
use AppBundle\Domain\Model\CompanyRole;
use Broadway\Serializer\SerializableInterface;

class AccountCompanyRoleAdded extends AbstractAccountEvent implements SerializableInterface
{
    /** @var CompanyRole */
    private $companyRole;

    public function __construct(AccountId $accountId, CompanyRole $companyRole)
    {
        parent::__construct($accountId);

        $this->companyRole = $companyRole;
    }

    /**
     * @return CompanyRole
     */
    public function getCompanyRole()
    {
        return $this->companyRole;
    }

    /**
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        return new AccountCompanyRoleAdded(
            new AccountId($data['account_id']),
            new CompanyRole($data['company_role'])
        );
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return [
            'account_id' => $this->getId()->getValue(),
            'company_role' => $this->companyRole->getValue(),
        ];
    }
}
