<?php

namespace AppBundle\Domain\Model;

class CompanyRole extends AbstractEnum
{
    const OWNER = 'owner';
    const ADMIN = 'admin';

    /**
     * value => title
     *
     * @return array
     */
    public static function all()
    {
        return [
            self::OWNER => 'company_role.owner',
            self::ADMIN => 'company_role.admin',
        ];
    }

    /**
     * @return CompanyRole
     */
    public static function owner()
    {
        return new CompanyRole(self::OWNER);
    }

    /**
     * @return CompanyRole
     */
    public static function admin()
    {
        return new CompanyRole(self::ADMIN);
    }
}
