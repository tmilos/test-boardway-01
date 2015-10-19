<?php

namespace AppBundle\Infra\AggregateFactory;

use Broadway\Domain\DomainEventStreamInterface;
use Broadway\EventSourcing\AggregateFactory\AggregateFactoryInterface;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Doctrine\Instantiator\Instantiator;

class DoctrineInstantiator implements AggregateFactoryInterface
{
    /** @var Instantiator */
    private $instantiator;

    /**
     *
     */
    public function __construct()
    {
        $this->instantiator = new Instantiator();
    }


    /**
     * @param string                     $aggregateClass the FQCN of the Aggregate to create
     * @param DomainEventStreamInterface $domainEventStream
     *
     * @return \Broadway\EventSourcing\EventSourcedAggregateRoot
     */
    public function create($aggregateClass, DomainEventStreamInterface $domainEventStream)
    {
        $aggregate = $this->instantiator->instantiate($aggregateClass);
        if ($aggregate instanceof EventSourcedAggregateRoot) {
            $aggregate->initializeState($domainEventStream);
        }

        return $aggregate;
    }

}
