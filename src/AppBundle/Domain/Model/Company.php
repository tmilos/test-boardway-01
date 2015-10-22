<?php

namespace AppBundle\Domain\Model;

use AppBundle\Domain\Event\CompanyCreatedEvent;
use Broadway\EventSourcing\EventSourcedAggregateRoot;

class Company extends EventSourcedAggregateRoot
{
    /** @var CompanyId */
    private $id;

    /** @var EmailAddress */
    private $ownerId;

    private $domain;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getAggregateRootId()
    {
        return $this->id->getValue();
    }

    /**
     * @param CompanyId    $id
     * @param EmailAddress $ownerId
     * @param string       $name
     * @param string       $domain
     *
     * @return Company
     */
    public static function create(CompanyId $id, EmailAddress $ownerId, $name, $domain)
    {
        $company = new Company();
        $company->apply(new CompanyCreatedEvent($id, $ownerId, $name, $domain));

        return $company;
    }

    protected function applyCompanyCreatedEvent(CompanyCreatedEvent $event)
    {
        $this->id = $event->getId();
        $this->ownerId = $event->getOwnerId();
        $this->domain = $event->getDomain();
    }
}
