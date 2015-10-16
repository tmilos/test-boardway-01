<?php

namespace AppBundle\Domain\Model;

interface EnumInterface
{
    /**
     * value => title
     *
     * @return array
     */
    public static function all();
}
