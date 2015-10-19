<?php

namespace AppBundle\Read\Model;

use Broadway\ReadModel\ReadModelInterface;
use Broadway\Serializer\SerializableInterface;

class Account extends AbstractSerializableReadModel
{
    /** @var string */
    public $id;

    /** @var string */
    public $email;

    /** @var string */
    public $password;

    /** @var string */
    public $salt;

    /** @var bool */
    public $isActive;

    /** @var \DateTime */
    public $createdAt;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

//
//    /**
//     * @return mixed The object instance
//     */
//    public static function deserialize(array $data)
//    {
//        $account = new Account();
//        $account->id = $data['id'];
//    }
//
//    /**
//     * @return array
//     */
//    public function serialize()
//    {
//        return [
//            'id' => $this->id,
//            'email' => $this->email,
//            'password' => $this->password,
//            'salt' => $this->salt,
//            'isActive' => $this->isActive ? 1 : 0,
//            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
//        ];
//    }
}
