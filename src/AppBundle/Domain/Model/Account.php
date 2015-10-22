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
        return $this->email->getValue();
    }

    protected function getChildEntities()
    {
        return [$this->companyStatus];
    }

    /**
     * @param EmailAddress    $id
     * @param EncodedPassword $encodedPassword
     *
     * @return Account
     */
    public static function signupForBusiness(EmailAddress $id, EncodedPassword $encodedPassword)
    {
        $account = new Account();
        $account->apply(new AccountCreatedEvent($id));
        $account->apply(new AccountPasswordSetEvent($id, $encodedPassword));
        $account->apply(new AccountSignedForBusinessEvent($id));

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

        $company = Company::create($companyId, $this->email, $this->email->getDomain(), $this->email->getDomain());

        $this->apply(new AccountBusinessVerifiedEvent($this->email, $companyId));

        return $company;
    }

    protected function applyAccountCreatedEvent(AccountCreatedEvent $event)
    {
        $this->email = $event->getId();
        $this->companyStatus = AccountCompanyStatus::none($this->email);
    }

    protected function applyAccountPasswordSetEvent(AccountPasswordSetEvent $event)
    {
        $this->encodedPassword = $event->getEncodedPassword();
    }

    protected function applyAccountSignedForBusinessEvent(AccountSignedForBusinessEvent $event)
    {
        $this->companyStatus = AccountCompanyStatus::pending($this->email);
    }

    protected function applyAccountBusinessVerifiedEvent(AccountBusinessVerifiedEvent $event)
    {
        $this->companyStatus = AccountCompanyStatus::active($this->email, $event->getCompanyId());
        $this->companyStatus->registerAggregateRoot($this);
        $this->companyStatus->addCompanyRole(CompanyRole::owner());
    }
}
