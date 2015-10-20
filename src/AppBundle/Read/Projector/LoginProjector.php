<?php

namespace AppBundle\Read\Projector;

use AppBundle\Domain\Event\AccountBusinessVerifiedEvent;
use AppBundle\Domain\Event\AccountCreatedEvent;
use AppBundle\Domain\Event\AccountPasswordSetEvent;
use AppBundle\Entity\Login;
use Broadway\ReadModel\Projector;
use Doctrine\Common\Persistence\ObjectManager;

class LoginProjector extends Projector
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

    public function applyAccountCreatedEvent(AccountCreatedEvent $event)
    {
        /** @var Login $login */
        $login = $this->manager->find(Login::class, $event->getId()->getValue());
        if (null == $login) {
            $login = new Login();
        }

        $login
            ->setId($event->getId()->getValue())
            ->setIsActive(false)
            ->setCreatedAt(new \DateTime())
            ->setEmail($event->getEmail()->getValue())
        ;
        $this->manager->persist($login);
        $this->manager->flush($login);
    }

    public function applyAccountPasswordSetEvent(AccountPasswordSetEvent $event)
    {
        /** @var Login $login */
        $login = $this->manager->find(Login::class, $event->getId()->getValue());
        $login->setPassword($event->getEncodedPassword()->getValue());
        $login->setSalt($event->getEncodedPassword()->getSalt());
        $this->manager->flush($login);
    }

    public function applyAccountBusinessVerifiedEvent(AccountBusinessVerifiedEvent $event)
    {
        /** @var Login $login */
        $login = $this->manager->find(Login::class, $event->getId()->getValue());
        $login->setIsActive(true);
        $this->manager->flush($login);
    }
}
