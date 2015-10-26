<?php

namespace AppBundle\Read\Model;

use AppBundle\Domain\Model\CompanyRole;
use Broadway\ReadModel\ReadModelInterface;
use Broadway\Serializer\SerializableInterface;

class Employee implements ReadModelInterface, SerializableInterface
{
    /** @var string */
    private $accountId;

    /** @var string */
    private $companyId;

    /** @var string[] */
    private $companyRoles = [];

    /**
     * @param string $accountId
     * @param string $companyId
     * @param array  $companyRoles
     */
    public function __construct($accountId, $companyId, array $companyRoles)
    {
        $this->accountId = $accountId;
        $this->companyId = $companyId;
        $this->setCompanyRoles($companyRoles);
    }

    /**
     * @return string
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * @return string
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * @return string[]
     */
    public function getCompanyRoles()
    {
        return $this->companyRoles;
    }

    /**
     * @param string $companyId
     *
     * @return Employee
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;

        return $this;
    }

    /**
     * @param \string[] $companyRoles
     *
     * @return Employee
     */
    public function setCompanyRoles(array $companyRoles)
    {
        $this->companyRoles = [];
        foreach ($companyRoles as $companyRole) {
            if ($companyRole instanceof CompanyRole) {
                $this->companyRoles[] = $companyRole->getValue();
            } elseif (is_string($companyRole)) {
                $this->companyRoles[] = $companyRole;
            } else {
                throw new \InvalidArgumentException($companyRole);
            }
        }

        return $this;
    }

    // ------------------------------------

    /**
     * @return string
     */
    public function getId()
    {
        return $this->accountId;
    }

    /**
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        return new Employee($data['accountId'], $data['companyId'], $data['companyRoles']);
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return [
            'accountId' => $this->accountId,
            'companyId' => $this->companyId,
            'companyRoles' => $this->companyRoles,
        ];
    }
}
