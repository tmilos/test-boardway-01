<?php

namespace AppBundle\Domain\Event;

use AppBundle\Domain\Model\AccountId;
use AppBundle\Domain\Model\EncodedPassword;
use Broadway\Serializer\SerializableInterface;

class AccountPasswordSetEvent extends AbstractAccountEvent implements SerializableInterface
{
    /** @var EncodedPassword */
    private $encodedPassword;

    /**
     * @param AccountId       $accountId
     * @param EncodedPassword $encodedPassword
     */
    public function __construct(AccountId $accountId, EncodedPassword $encodedPassword)
    {
        parent::__construct($accountId);

        $this->encodedPassword = $encodedPassword;
    }

    /**
     * @return EncodedPassword
     */
    public function getEncodedPassword()
    {
        return $this->encodedPassword;
    }

    /**
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        return new AccountPasswordSetEvent(
            new AccountId($data['account_id']),
            new EncodedPassword($data['encoded_password']['value'], $data['encoded_password']['salt'])
        );
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return [
            'account_id' => $this->getId()->getValue(),
            'encoded_password' => [
                'value' => $this->encodedPassword->getValue(),
                'salt' => $this->encodedPassword->getSalt(),
            ]
        ];
    }
}
