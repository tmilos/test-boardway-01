<?php

namespace AppBundle\Read\Projector;

use AppBundle\Domain\Event\CompanyCreatedEvent;
use AppBundle\Entity\Company;
use Broadway\ReadModel\Projector;
use Doctrine\Common\Persistence\ObjectManager;

class CompanyProjector extends Projector
{
    /** @var ObjectManager */
    private $manager;

    /**
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param CompanyCreatedEvent $event
     */
    public function applyCompanyCreatedEvent(CompanyCreatedEvent $event)
    {
        $company = $this->manager->find(Company::class, $event->getId()->getValue());
        if (null == $company) {
            $company = new Company();
        }

        $company
            ->setId($event->getId()->getValue())
            ->setName($event->getName())
            ->setDomain($event->getDomain())
        ;

        $this->manager->persist($company);
        $this->manager->flush($company);
    }
}
