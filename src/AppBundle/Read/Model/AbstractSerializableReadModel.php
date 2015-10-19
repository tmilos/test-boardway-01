<?php

namespace AppBundle\Read\Model;

use Broadway\ReadModel\ReadModelInterface;
use Broadway\Serializer\SerializableInterface;

abstract class AbstractSerializableReadModel implements ReadModelInterface, SerializableInterface
{
    /**
     * @return mixed The object instance
     */
    public static function deserialize(array $data)
    {
        $object = new $data['class']();
        foreach ($data['payload'] as $k=>$v) {
            $object->{$k} = $v;
        }
    }

    /**
     * @return array
     */
    public function serialize()
    {
        return json_encode($this);
    }
}
