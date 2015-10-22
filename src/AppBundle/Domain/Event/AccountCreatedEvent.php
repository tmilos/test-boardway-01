<?php

namespace AppBundle\Domain\Event;

use Broadway\Serializer\SerializableInterface;

class AccountCreatedEvent extends AbstractAccountEvent implements SerializableInterface
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
