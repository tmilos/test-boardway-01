<?php

namespace AppBundle\Domain\CommandHandler;

use AppBundle\Domain\Command\SignupForBusinessCommand;
use AppBundle\Domain\Model\Account;
use AppBundle\Domain\Repository\AccountRepository;
use Broadway\CommandHandling\CommandHandler;

class SignupForBusinessCommandHandler extends CommandHandler
{
    /** @var AccountRepository */
    private $accountRepository;

    /**
     * @param AccountRepository $accountRepository
     */
    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function handleSignupForBusinessCommand(SignupForBusinessCommand $command)
    {
        $account = Account::signupForBusiness($command->getEmailAddress(), $command->getEncodedPassword());

        $this->accountRepository->save($account);
    }
}
