<?php

namespace CrudSys\OAuth2\Client\Entity;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;
use CrudSys\OAuth2\Client\Helper\Helper;

class User implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    protected array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function toArray(): array
    {
        return $this->response;
    }

    private function validateString(string $key): string
    {
        $value = $this->getValueByKey($this->response, $key);

        if(!is_string($value) || Helper::isEmptyOrNull($value)) {
            $value = '';
        }

        return $value;
    }

    private function validateBool(string $key): bool
    {
        $value = (bool) $this->getValueByKey($this->response, $key);

        if(!is_bool($value) || Helper::isEmptyOrNull($value)) {
            $value = false;
        }

        return $value;
    }

    private function validateInt(string $key): int
    {
        $value = (int) $this->getValueByKey($this->response, $key);

        if(!is_int($value) || Helper::isEmptyOrNull($value)) {
            $value = 0;
        }

        return $value;
    }

    public function getId(): string
    {
        return $this->validateString('id');
    }

    public function getUsername(): string
    {
        return $this->validateString('username');
    }

    public function isPatron(): bool
    {
        return $this->validateBool('patron');
    }

    public function isOnline(): bool
    {
        return $this->validateBool('online');
    }

    public function getProfile(): array
    {
        return $this->getValueByKey($this->response, 'profile');
    }

    public function getCountry(): string
    {
        return $this->validateString('profile.country');
    }

    public function getLocation(): string
    {
        return $this->validateString('profile.location');
    }

    public function getBio(): string
    {
        return $this->validateString('profile.bio');
    }

    public function getFirstName(): string
    {
        return $this->validateString('profile.firstName');
    }

    public function getLastName(): string
    {
        return $this->validateString('profile.lastName');
    }

    public function getLinks(): string
    {
        return $this->validateString('profile.links');
    }

    public function getUrl(): string
    {
        return $this->validateString('url');
    }

    public function getCompletionRate(): int
    {
        return $this->validateInt('completionRate');
    }

    public function getBlocking(): int
    {
        return $this->validateInt('blocking');
    }
}
