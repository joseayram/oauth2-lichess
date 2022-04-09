<?php

if (!function_exists('isEmptyOrNull')) {
    function isEmptyOrNull($value): bool
    {
        return is_null($value) || empty($value) || '' === $value;
    }
}

if (!function_exists('base64URLEncode')) {
    /**
     * base64URLEncode
     *
     * @source https://base64.guru/developers/php/examples/base64url
     */
    function base64URLEncode(string $string): string
    {
        // First of all you should encode $data to Base64 string
        $b64 = base64_encode($string);

        // Make sure you get a valid result, otherwise, return FALSE, as the base64_encode() function do
        if ($b64 === false) {
            return false;
        }

        // Convert Base64 to Base64URL by replacing “+” with “-” and “/” with “_”
        $url = strtr($b64, '+/', '-_');

        // Remove padding character from the end of line and return the Base64URL result
        return rtrim($url, '=');
    }
}

if (!function_exists('createVerifier')) {
    function createVerifier(): string
    {
        $random = bin2hex(openssl_random_pseudo_bytes(32));

        return \base64URLEncode(pack('H*', $random));
    }
}

if (!function_exists('createChallenge')) {
    function createChallenge(string $verifier): string
    {
        return \base64URLEncode(pack('H*', hash('sha256', $verifier)));
    }
}
