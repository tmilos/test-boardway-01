<?php

namespace AppBundle\Domain\Event;

use AppBundle\Domain\Model\CompanyId;
use AppBundle\Domain\Model\EmailAddress;
use Broadway\Serializer\SerializableInterface;

class AccountBusinessVerifiedEvent extends AbstractAccountEvent implements SerializableInterface
{
    /** @var CompanyId */
    private $companyId;

    /**
     * @param EmailAddress $accountId
     * @param CompanyId    $companyId
     */
    public function __construct(EmailAddress $accountId, CompanyId $companyId)
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
            new EmailAddress($data['account_id']),
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
