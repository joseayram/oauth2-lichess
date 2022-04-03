<?php

namespace CrudSys\OAuth2\Client\Entity;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;
use CrudSys\OAuth2\Client\Helper\Helper;

class User implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    /**
     * Raw response
     */
    protected array $response;

    /**
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function toArray(): array
    {
        return $this->response;
    }

    private function validateString($value): string
    {
        if( !is_string($value) || Helper::isEmptyOrNull($value)) {
            $value = '';
        }

        return $value;
    }

    public function getId(): string
    {
        return $this->validateString( $this->getValueByKey($this->response, 'id') );
    }

    public function getUsername(): string
    {
        return $this->validateString( $this->getValueByKey($this->response, 'username') );
    }

    public function isPatron(): bool
    {
        return $this->getValueByKey($this->response, 'patron');
    }

    public function isOnline(): bool
    {
        return $this->getValueByKey($this->response, 'online');
    }

    public function getProfile(): array
    {
        return $this->getValueByKey($this->response, 'profile');
    }

    public function getCountry(): string
    {
        return $this->validateString( $this->getValueByKey($this->response, 'profile.country') );
    }

    public function getLocation(): string
    {
        return $this->validateString( $this->getValueByKey($this->response, 'profile.location') );
    }

    public function getBio(): string
    {
        return $this->validateString( $this->getValueByKey($this->response, 'profile.bio') );
    }

    public function getFirstName(): string
    {
        return $this->validateString( $this->getValueByKey($this->response, 'profile.firstName') );
    }

    public function getLastName(): string
    {
        return $this->validateString( $this->getValueByKey($this->response, 'profile.lastName') );
    }

    public function getLinks(): string
    {
        return $this->validateString( $this->getValueByKey($this->response, 'profile.links') );
    }

    public function getUrl(): string
    {
        return $this->validateString( $this->getValueByKey($this->response, 'url') );
    }

    public function getCompletionRate(): int
    {
        return $this->getValueByKey($this->response, 'completionRate');
    }
}
