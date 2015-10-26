<?php

namespace AppBundle\Read\Projector;

use AppBundle\Domain\Event\EmployeeRolesSetEvent;
use AppBundle\Domain\Event\CompanyCreatedEvent;
use AppBundle\Domain\Model\CompanyRole;
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

    public function applyEmployeeRolesSetEvent(EmployeeRolesSetEvent $event)
    {
        /** @var Employee $employee */
        $employee = $this->employeeRepository->find($event->getId()->getValue());
        if (null == $employee) {
            $employee = new Employee(
                $event->getId()->getValue(),
                $event->getCompanyId()->getValue(),
                $event->getCompanyRoles()
            );
        } else {
            $employee
                ->setCompanyRoles($event->getCompanyRoles())
                ->setCompanyId($event->getId()->getValue());
        }
        $this->employeeRepository->save($employee);
    }
}
