<?php

namespace AppBundle\Read\Model;

use Broadway\ReadModel\ReadModelInterface;
use Broadway\Serializer\SerializableInterface;

class Employee implements ReadModelInterface, SerializableInterface
{
    /** @var string */
    private $accountId;

    /** @var string */
    private $companyId;

    /**
     * @param string $accountId
     * @param string $companyId
     */
    public function __construct($accountId, $companyId)
    {
        $this->accountId = $accountId;
        $this->companyId = $companyId;
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
     * @param string $companyId
     *
     * @return Employee
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;

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
        return new Employee($data['accountId'], $data['companyId']);
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return [
            'accountId' => $this->accountId,
            'companyId' => $this->companyId,
        ];
    }
}
