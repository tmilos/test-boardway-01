<?php

namespace AppBundle\Read\Projector;

use AppBundle\Domain\Event\CompanyCreatedEvent;
use AppBundle\Read\Model\Company;
use Broadway\ReadModel\Projector;
use Broadway\ReadModel\RepositoryInterface;

class CompanyProjector extends Projector
{
    /** @var RepositoryInterface */
    private $repository;

    /**
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function applyCompanyCreatedEvent(CompanyCreatedEvent $event)
    {
        $company = $this->repository->find($event->getId()->getValue());
        if (null == $company) {
            $company = new Company($event->getId()->getValue(), $event->getName(), $event->getDomain());
        }
        $company->setName($event->getName());
        $company->setDomain($event->getDomain());
        $this->repository->save($company);
    }
}
