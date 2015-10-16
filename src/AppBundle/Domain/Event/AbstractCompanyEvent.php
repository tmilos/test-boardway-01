<?php

namespace AppBundle\Domain\Event;

use AppBundle\Domain\Model\CompanyId;

abstract class AbstractCompanyEvent
{
    /** @var CompanyId */
    private $id;

    /**
     * @param CompanyId $id
     */
    public function __construct(CompanyId $id)
    {
        $this->id = $id;
    }

    /**
     * @return CompanyId
     */
    public function getId()
    {
        return $this->id;
    }
}
