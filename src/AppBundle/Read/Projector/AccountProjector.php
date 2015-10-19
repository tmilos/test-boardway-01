<?php

namespace AppBundle\Read\Projector;

use AppBundle\Domain\Event\AccountBusinessVerifiedEvent;
use AppBundle\Domain\Event\AccountCreatedEvent;
use AppBundle\Domain\Event\AccountPasswordSetEvent;
use AppBundle\Read\Model\Account;
use Broadway\ReadModel\Projector;
use Broadway\ReadModel\RepositoryInterface;

class AccountProjector extends Projector
{
    /** @var RepositoryInterface */
    private $accountRepository;

    /**
     * @param RepositoryInterface $accountRepository
     */
    public function __construct(RepositoryInterface $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function applyAccountCreatedEvent(AccountCreatedEvent $event)
    {
        $account = new Account();
        $account->id = $event->getId()->getValue();
        $account->isActive = 0;
        $this->accountRepository->save($account);
    }

    public function applyAccountPasswordSetEvent(AccountPasswordSetEvent $event)
    {
        /** @var Account $account */
        $account = $this->accountRepository->find($event->getId()->getValue());
        $account->password = $event->getEncodedPassword()->getValue();
        $account->salt = $event->getEncodedPassword()->getSalt();
        $this->accountRepository->save($account);
    }

    public function applyAccountBusinessVerifiedEvent(AccountBusinessVerifiedEvent $event)
    {
        /** @var Account $account */
        $account = $this->accountRepository->find($event->getId()->getValue());
        $account->isActive = 1;
        $this->accountRepository->save($account);
    }
}
