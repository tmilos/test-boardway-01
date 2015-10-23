<?php

namespace AppBundle\Read\Model;

use Broadway\ReadModel\ReadModelInterface;
use Broadway\Serializer\SerializableInterface;

class Company implements ReadModelInterface, SerializableInterface
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $domain;

    /**
     * @param string $id
     * @param string $name
     * @param string $domain
     */
    public function __construct($id, $name, $domain)
    {
        $this->id = $id;
        $this->name = $name;
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $name
     *
     * @return Company
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $domain
     *
     * @return Company
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    // ----------------------------

    /**
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        return new Company($data['id'], $data['name'], $data['domain']);
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'domain' => $this->domain,
        ];
    }
}
