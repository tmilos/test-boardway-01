<?php

namespace AppBundle\Domain\Model;

use AppBundle\Domain\Error\DomainOperationException;
use AppBundle\Domain\Event\AccountBusinessVerifiedEvent;
use AppBundle\Domain\Event\AccountCreatedEvent;
use AppBundle\Domain\Event\AccountPasswordSetEvent;
use AppBundle\Domain\Event\AccountSignedForBusinessEvent;
use Broadway\EventSourcing\EventSourcedAggregateRoot;

class Account extends EventSourcedAggregateRoot
{
    /** @var AccountId */
    private $id;

    /** @var EmailAddress */
    private $email;

    /** @var EncodedPassword */
    private $encodedPassword;

    /** @var  AccountCompanyStatus */
    private $companyStatus;

    /**
     *
     */
    private function __construct()
    {
    }

    /**
     * @return string
     */
    public function getAggregateRootId()
    {
        return $this->id->getValue();
    }

    protected function getChildEntities()
    {
        return [$this->companyStatus];
    }

    /**
     * @param AccountId       $id
     * @param EmailAddress    $email
     * @param EncodedPassword $encodedPassword
     *
     * @return Account
     */
    public static function signupForBusiness(AccountId $id, EmailAddress $email, EncodedPassword $encodedPassword)
    {
        $account = new Account();
        $account->apply(new AccountCreatedEvent($id, $email));
        $account->apply(new AccountPasswordSetEvent($id, $encodedPassword));
        $account->apply(new AccountSignedForBusinessEvent($id, $email));

        return $account;
    }

    /**
     * @param CompanyId $companyId
     *
     * @return Company
     */
    public function verifyBusiness(CompanyId $companyId)
    {
        if ($this->companyStatus->getValue() != AccountCompanyStatus::PENDING) {
            throw new DomainOperationException('Account can be verified for business only in pending status');
        }

        $company = Company::create($companyId, $this->id, $this->email->getDomain(), $this->email->getDomain());

        $this->apply(new AccountBusinessVerifiedEvent($this->id, $companyId));

        return $company;
    }

    protected function applyAccountCreatedEvent(AccountCreatedEvent $event)
    {
        $this->id = $event->getId();
        $this->email = $event->getEmail();
        $this->companyStatus = AccountCompanyStatus::none($this->id);
    }

    protected function applyAccountPasswordSetEvent(AccountPasswordSetEvent $event)
    {
        $this->encodedPassword = $event->getEncodedPassword();
    }

    protected function applyAccountSignedForBusinessEvent(AccountSignedForBusinessEvent $event)
    {
        $this->companyStatus = AccountCompanyStatus::pending($this->id);
    }

    protected function applyAccountBusinessVerifiedEvent(AccountBusinessVerifiedEvent $event)
    {
        $this->companyStatus = AccountCompanyStatus::active($this->id, $event->getCompanyId());
        $this->companyStatus->registerAggregateRoot($this);
        $this->companyStatus->addCompanyRole(CompanyRole::owner());
    }
}
