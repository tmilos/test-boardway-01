<?php

namespace AppBundle\Domain\Repository;

use AppBundle\Domain\Model\Account;
use AppBundle\Infra\AggregateFactory\DoctrineInstantiator;
use Broadway\EventHandling\EventBusInterface;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStoreInterface;

class AccountRepository extends EventSourcingRepository
{
    /**
     * @param EventStoreInterface $eventStore
     * @param EventBusInterface   $eventBus
     */
    public function __construct(EventStoreInterface $eventStore, EventBusInterface $eventBus)
    {
        parent::__construct($eventStore, $eventBus, Account::class, new DoctrineInstantiator());
    }
}
