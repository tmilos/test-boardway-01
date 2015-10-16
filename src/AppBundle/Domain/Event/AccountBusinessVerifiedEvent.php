<?php

namespace AppBundle\Domain\Event;

use AppBundle\Domain\Model\AccountId;
use AppBundle\Domain\Model\CompanyId;
use Broadway\Serializer\SerializableInterface;

class AccountBusinessVerifiedEvent extends AbstractAccountEvent implements SerializableInterface
{
    /** @var CompanyId */
    private $companyId;

    /**
     * @param AccountId $accountId
     * @param CompanyId $companyId
     */
    public function __construct(AccountId $accountId, CompanyId $companyId)
    {
        parent::__construct($accountId);

        $this->companyId = $companyId;
    }

    /**
     * @return CompanyId
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        return new AccountBusinessVerifiedEvent(
            new AccountId($data['account_id']),
            new CompanyId($data['company_id'])
        );
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return [
            'account_id' => $this->getId()->getValue(),
            'company_id' => $this->getCompanyId()->getValue()
        ];
    }
}
