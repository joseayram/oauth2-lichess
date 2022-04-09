<?php
namespace CrudSys\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;
use CrudSys\OAuth2\Client\Entity\User;

class Lichess extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public const SCOPE_PREFERENCE_READ = 'preference:read';
    public const SCOPE_PREFERENCE_WRITE = 'preference:write';
    public const SCOPE_EMAIL = 'email:read';
    public const SCOPE_CHALLENGE_READ = 'challenge:read';
    public const SCOPE_CHALLENGE_WRITE = 'challenge:write';
    public const SCOPE_CHALLENGE_BULK = 'challenge:bulk';

    /**
     * Code Challenge Method. Defaults to S256. Available options are:
     *  * S256
     * @var string
     */
    protected $code_challenge_method;

    public function __construct(array $options = [ ], array $collaborators = [ ])
    {
        $options['code_challenge_method'] = 'S256';
        parent::__construct($options, $collaborators);
    }

    public function getBaseAuthorizationUrl(): string
    {
        return 'https://lichess.org/oauth';
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return 'https://lichess.org/api/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return 'https://lichess.org/api/account';
    }

    protected function getDefaultScopes(): array
    {
        return [];
    }

    protected function getScopeSeparator(): string
    {
        return ' ';
    }

    /**
     * @param array<string, mixed>|string $data
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() !== 200) {
            $errorDescription = '';
            $error = '';
            if (\is_array($data) && !empty($data)) {
                $errorDescription = $data['error_description'] ?? $data['message'];
                $error = $data['error'];
            }
            throw new IdentityProviderException(
                sprintf("%d - %s: %s", $response->getStatusCode(), $error, $errorDescription),
                $response->getStatusCode(),
                $data
            );
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token): User
    {
        return new User($response);
    }
}
