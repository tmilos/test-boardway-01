<?php

namespace AppBundle\Read\Model;

use Broadway\ReadModel\ReadModelInterface;
use Broadway\Serializer\SerializableInterface;
use Symfony\Component\Security\Core\Role\Role;

class User implements  ReadModelInterface, SerializableInterface
{
    /** @var string */
    private $email;

    /** @var string */
    private $password;

    /** @var string */
    private $salt;

    /** @var string[] */
    private $roles = [];

    private $isActive = false;

    // SETTERS ------------------------

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @param \string[] $roles
     *
     * @return User
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = (bool)$isActive;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @return \string[]
     */
    public function getRoles()
    {
        return $this->roles;
    }

    // -------------------------------

    /**
     * @return string
     */
    public function getId()
    {
        return $this->email;
    }

    // ------------------------------

    /**
     * @param array $data
     *
     * @return User
     */
    public static function deserialize(array $data)
    {
        $object = new static();
        $object->email = $data['email'];
        $object->password = $data['password'];
        $object->salt = $data['salt'];
        $object->roles = $data['roles'];
        $object->isActive = $data['is_active'];

        return $object;
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
            'salt' => $this->salt,
            'roles' => $this->roles,
            'is_active' => $this->isActive,
        ];
    }
}
