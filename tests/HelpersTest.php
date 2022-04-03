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
}
