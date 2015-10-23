<?php

namespace AppBundle\Read\Projector;

use AppBundle\Domain\Event\CompanyCreatedEvent;
use AppBundle\Read\Model\Employee;
use Broadway\ReadModel\Projector;
use Broadway\ReadModel\RepositoryInterface;

class EmployeeProjector extends Projector
{
    /** @var RepositoryInterface */
    private $employeeRepository;

    /**
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->employeeRepository = $repository;
    }

    /**
     * @param CompanyCreatedEvent $event
     */
    public function applyCompanyCreatedEvent(CompanyCreatedEvent $event)
    {
        /** @var Employee $employee */
        $employee = $this->employeeRepository->find($event->getOwnerId()->getValue());
        if (null == $employee) {
            $employee = new Employee($event->getOwnerId()->getValue(), $event->getId()->getValue());
        } else {
            $employee->setCompanyId($event->getId()->getValue());
        }
        $this->employeeRepository->save($employee);
    }
}
