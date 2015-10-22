<?php

namespace AppBundle\Read\Projector;

use AppBundle\Domain\Event\AccountBusinessVerifiedEvent;
use AppBundle\Domain\Event\AccountCreatedEvent;
use AppBundle\Domain\Event\AccountPasswordSetEvent;
use AppBundle\Read\Model\User;
use Broadway\ReadModel\ElasticSearch\ElasticSearchRepository;
use Broadway\ReadModel\Projector;

class UserProjector extends Projector
{
    /** @var ElasticSearchRepository */
    private $repository;

    /**
     * @param ElasticSearchRepository $repository
     */
    public function __construct(ElasticSearchRepository $repository)
    {
        $this->repository = $repository;
    }

    public function applyAccountCreatedEvent(AccountCreatedEvent $event)
    {
        /** @var User $user */
        $user = $this->repository->find($event->getId()->getValue());
        if (null == $user) {
            $user = new User();
        }

        $user
            ->setEmail($event->getId()->getValue())
            ->setRoles(['ROLE_USER'])
            ->setIsActive(false)
        ;

        $this->repository->save($user);
    }

    public function applyAccountPasswordSetEvent(AccountPasswordSetEvent $event)
    {
        /** @var User $user */
        $user = $this->repository->find($event->getId()->getValue());
        $user->setPassword($event->getEncodedPassword()->getValue());
        $user->setSalt($event->getEncodedPassword()->getSalt());
        $this->repository->save($user);
    }

    public function applyAccountBusinessVerifiedEvent(AccountBusinessVerifiedEvent $event)
    {
        /** @var User $user */
        $user = $this->repository->find($event->getId()->getValue());
        $user->setIsActive(true);
        $this->repository->save($user);
    }
}
