<?php declare(strict_types=1);

namespace CrudSys\OAuth2\Client\Test;

use PHPUnit\Framework\TestCase;

final class HelpersTest extends TestCase
{
    public function testIsEmptyOrNull(): void
    {
        $value = '';
        $this->assertTrue(\isEmptyOrNull($value));

        $value = 'something';
        $this->assertFalse(\isEmptyOrNull($value));
    }

    public function testBase64URLEncode(): void
    {
        $randomString = 'this.is.a.random.string.very.very.large.random.string';
        $base64Encode = base64_encode($randomString);
        $base64URLEncode = \base64URLEncode($randomString);

        $this->assertStringContainsString($base64URLEncode, $base64Encode);
        $this->assertStringNotContainsString('=', $base64URLEncode);
        $this->assertStringNotContainsString('-', $base64URLEncode);
        $this->assertStringNotContainsString('+', $base64URLEncode);
    }
}
