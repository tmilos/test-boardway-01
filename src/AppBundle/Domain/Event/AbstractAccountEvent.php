<?php

namespace AppBundle\Domain\Event;

use AppBundle\Domain\Model\EmailAddress;

abstract class AbstractAccountEvent
{
    /** @var EmailAddress */
    private $id;

    /**
     * @param EmailAddress $accountId
     */
    public function __construct(EmailAddress $accountId)
    {
        $this->id = $accountId;
    }

    /**
     * @return EmailAddress
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed The object instance
     */
    protected static function _deserialize(array $data)
    {
        return new static(new EmailAddress($data['account_id']));
    }

    /**
     * @return array
     */
    protected function _serialize()
    {
        return [
            'account_id' => $this->getId()->getValue(),
        ];
    }
}
