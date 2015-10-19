<?php

namespace AppBundle\Domain\Event;

use AppBundle\Domain\Model\AccountId;
use AppBundle\Domain\Model\EmailAddress;
use Broadway\Serializer\SerializableInterface;

class AccountCreatedEvent extends AbstractAccountEvent implements SerializableInterface
{
    /** @var EmailAddress */
    private $email;

    public function __construct(AccountId $accountId, EmailAddress $email)
    {
        parent::__construct($accountId);

        $this->email = $email;
    }

    /**
     * @return EmailAddress
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        return new AccountCreatedEvent(
            new AccountId($data['account_id']),
            new EmailAddress($data['email'])
        );
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return [
            'account_id' => $this->getId()->getValue(),
            'email' => $this->getEmail()->getValue(),
        ];
    }
}
