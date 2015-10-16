<?php

namespace AppBundle\Domain\Event;

use AppBundle\Domain\Model\AccountId;

abstract class AbstractAccountEvent
{
    /** @var AccountId */
    private $id;

    /**
     * @param AccountId $accountId
     */
    public function __construct(AccountId $accountId)
    {
        $this->id = $accountId;
    }

    /**
     * @return AccountId
     */
    public function getId()
    {
        return $this->id;
    }
}
