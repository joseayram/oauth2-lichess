<?php declare(strict_types=1);

namespace CrudSys\OAuth2\Client\Test\Helper;

use PHPUnit\Framework\TestCase;
use CrudSys\OAuth2\Client\Helper\Helper;

final class HelperTest extends TestCase
{
    public function testIsEmptyOrNull(): void
    {
        $value = '';
        $this->assertTrue(Helper::isEmptyOrNull($value));

        $value = 'something';
        $this->assertFalse(Helper::isEmptyOrNull($value));
    }
}
