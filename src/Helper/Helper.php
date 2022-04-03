<?php
namespace CrudSys\OAuth2\Client\Helper;

class Helper
{
    /**
     * isEmptyOrNull
     * Verify is a string is Empty or Null
     *
     * @param [mixed] $value
     * @return boolean
     */
    public static function isEmptyOrNull($value): bool
    {
        return is_null($value) || empty($value) || '' === $value;
    }
}
