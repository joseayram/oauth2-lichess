<?php

if(!function_exists('isEmptyOrNull')) {
    function isEmptyOrNull($value): bool {
        return is_null($value) || empty($value) || '' === $value;
    }
}
