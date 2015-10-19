<?php

namespace AppBundle\Domain\CommandHandler;

use AppBundle\Domain\Command\VerifyBusinessCommand;
use AppBundle\Domain\Model\Account;
use AppBundle\Domain\Repository\AccountRepository;
use AppBundle\Domain\Repository\CompanyRepository;
use Broadway\CommandHandling\CommandHandler;

class VerifyBusinessCommandHandler extends CommandHandler
{
    /** @var AccountRepository */
    private $accountRepository;

    /** @var CompanyRepository */
    private $companyRepository;

    /**
     * @param AccountRepository $accountRepository
     * @param CompanyRepository $companyRepository
     */
    public function __construct(AccountRepository $accountRepository, CompanyRepository $companyRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->companyRepository = $companyRepository;
    }

    public function handleVerifyBusinessCommand(VerifyBusinessCommand $command)
    {
        /** @var Account $account */
        $account = $this->accountRepository->load($command->getAccountId()->getValue());
        $company = $account->verifyBusiness($command->getCompanyId());

        $this->companyRepository->save($company);
        $this->accountRepository->save($account);
    }
}
