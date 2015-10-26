<?php

namespace AppBundle\Domain\Event;

use AppBundle\Domain\Model\CompanyId;
use AppBundle\Domain\Model\CompanyRole;
use AppBundle\Domain\Model\EmailAddress;
use Broadway\Serializer\SerializableInterface;

class EmployeeRolesSetEvent extends AbstractAccountEvent implements SerializableInterface
{
    /** @var CompanyId */
    private $companyId;

    /** @var CompanyRole[] */
    private $companyRoles;

    /**
     * @param EmailAddress  $accountId
     * @param CompanyId     $companyId
     * @param CompanyRole[] $companyRoles
     */
    public function __construct(EmailAddress $accountId, CompanyId $companyId, array $companyRoles)
    {
        parent::__construct($accountId);

        $this->companyId = $companyId;
        $this->companyRoles = $companyRoles;
    }

    /**
     * @return CompanyId
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * @return CompanyRole[]
     */
    public function getCompanyRoles()
    {
        return $this->companyRoles;
    }

    /**
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        return new EmployeeRolesSetEvent(
            new EmailAddress($data['accountId']),
            new CompanyId($data['companyId']),
            array_map(function ($role) {
                return new CompanyRole($role);
            }, $data['companyRoles'])
        );
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return [
            'accountId' => $this->getId()->getValue(),
            'companyId' => $this->companyId->getValue(),
            'companyRoles' => array_map(function (CompanyRole $companyRole) {
                return $companyRole->getValue();
            }, $this->companyRoles),
        ];
    }
}
