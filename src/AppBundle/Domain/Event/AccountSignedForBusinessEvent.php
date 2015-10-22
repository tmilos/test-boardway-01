<?php

namespace AppBundle\Domain\Event;

use AppBundle\Domain\Model\AccountId;
use AppBundle\Domain\Model\EmailAddress;
use Broadway\Serializer\SerializableInterface;

class AccountSignedForBusinessEvent extends AbstractAccountEvent implements SerializableInterface
{
    /**
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        return static::_deserialize($data);
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return static::_serialize();
    }
}
