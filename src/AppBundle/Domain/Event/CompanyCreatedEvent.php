<?php

namespace AppBundle\Domain\Event;

use AppBundle\Domain\Model\AccountId;
use AppBundle\Domain\Model\CompanyId;
use Broadway\Serializer\SerializableInterface;

class CompanyCreatedEvent extends AbstractCompanyEvent implements SerializableInterface
{
    /** @var AccountId */
    private $ownerId;

    /** @var string */
    private $name;

    /** @var string */
    private $domain;

    /**
     * @param CompanyId $id
     * @param AccountId $ownerId
     * @param string    $name
     * @param string    $domain
     */
    public function __construct(CompanyId $id, AccountId $ownerId, $name, $domain)
    {
        parent::__construct($id);
    }

    /**
     * @return AccountId
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        return new CompanyCreatedEvent(
            new CompanyId($data['company_id']),
            new AccountId($data['owner_id']),
            $data['name'],
            $data['domain']
        );
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return [
            'company_id' => $this->getId()->getValue(),
            'owner_id' => $this->ownerId->getValue(),
            'name' => $this->name,
            'domain' => $this->domain,
        ];
    }
}
