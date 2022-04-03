<?php declare(strict_types=1);

namespace CrudSys\OAuth2\Client\Test\Entity;

use CrudSys\OAuth2\Client\Entity\User;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    use ArrayAccessorTrait;

    private User $user;
    private $response;

    protected function setUp(): void
    {
        $this->response = [
            'id' => 'garrykasparov',
            'username' => 'garrykasparov',
            'patron' => 0,
            'online' => 1,
            'profile' => [
                'country' => 'RU',
                'location' => 'Garik Kimovich Weinstein',
                'bio' => 'Russian chess grandmaster, former World Chess Champion, writer, political activist and commentator',
                'firstName' => 'Garry',
                'lastName' => 'Kasparov',
                'links' => ''
            ],
            'url' => 'https://lichess.org/@/garrykasparov',
            'completionRate' => 100000,
        ];

        $this->user = new User($this->response);
    }

    private function getResponseKey(string $key)
    {
        return $this->getValueByKey($this->response, $key);
    }

    public function testToArray(): void
    {
        $this->assertIsArray($this->user->toArray());
        $this->assertEquals($this->response, $this->user->toArray());
    }

    public function testGetId(): void
    {
        $this->assertEquals($this->getResponseKey('id'), $this->user->getId());
    }

    public function testGetUsername(): void
    {
        $this->assertEquals($this->getResponseKey('username'), $this->user->getUsername());
    }

    public function testIsPatron(): void
    {
        $this->assertFalse($this->user->isPatron());
    }

    public function testIsOnline(): void
    {
        $this->assertTrue($this->user->isOnline());
    }

    public function testGetProfile(): void
    {
        $this->assertIsArray($this->getResponseKey('profile'));
        $this->assertEquals($this->getResponseKey('profile'), $this->user->getProfile());
    }

    public function testGetCountry(): void
    {
        $this->assertEquals($this->getResponseKey('profile.country'), $this->user->getCountry());
    }

    public function testGetLocation(): void
    {
        $this->assertEquals($this->getResponseKey('profile.location'), $this->user->getLocation());
    }

    public function testGetBio(): void
    {
        $this->assertEquals($this->getResponseKey('profile.bio'), $this->user->getBio());
    }

    public function testGetFirstName(): void
    {
        $this->assertEquals($this->getResponseKey('profile.firstName'), $this->user->getFirstName());
    }

    public function testGetLastName(): void
    {
        $this->assertEquals($this->getResponseKey('profile.lastName'), $this->user->getLastName());
    }

    public function testGetLinks(): void
    {
        $this->assertEquals($this->getResponseKey('profile.links'), $this->user->getLinks());
    }

    public function testGetUrl(): void
    {
        $this->assertEquals($this->getResponseKey('url'), $this->user->getUrl());
    }

    public function testGetCompletionRate(): void
    {
        $this->assertEquals($this->getResponseKey('completionRate'), $this->user->getCompletionRate());
    }

    public function testGetBlocking(): void
    {
        $this->assertEquals($this->getResponseKey('blocking'), $this->user->getBlocking());
    }
}
