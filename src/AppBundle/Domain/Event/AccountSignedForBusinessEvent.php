<?php

namespace AppBundle\Domain\Event;

use Broadway\Serializer\SerializableInterface;

class AccountSignedForBusinessEvent extends AbstractAccountEvent implements SerializableInterface
{
    /**
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        return new AccountSignedForBusinessEvent($data['account_id']);
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return [
            'account_id' => $this->getId()->getValue(),
        ];
    }
}
