<?php declare(strict_types=1);

namespace CrudSys\OAuth2\Client\Test\Provider;

use PHPUnit\Framework\TestCase;
use CrudSys\OAuth2\Client\Provider\Lichess;
use CrudSys\OAuth2\Client\Entity\User;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\QueryBuilderTrait;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\ClientInterface;

final class LichessTest extends TestCase
{
    use QueryBuilderTrait;

    const BASEURL = 'https://lichess.org';
    protected Lichess $provider;

    protected function setUp(): void
    {
        $this->provider = new Lichess([
            'clientId' => 'client_id',
            'clientSecret' => 'client_secret',
            'redirectUri' => 'redirect_uri'
        ]);
    }

    private function createMockResponse(string $responseBody, int $statusCode = 200): MockObject
    {
        $response = $this->createMock(ResponseInterface::class);

        $response->method('getStatusCode')
            ->willReturn($statusCode);

        $response->method('getBody')
            ->willReturn($responseBody);

        $response->method('getHeader')
            ->with('content-type')
            ->willReturn('application/json');

        return $response;
    }

    public function testBaseAuthorizationUrl(): void
    {
        $this->assertEquals(self::BASEURL. '/oauth', $this->provider->getBaseAuthorizationUrl());
    }

    public function testBaseAccessTokenUrl(): void
    {
        $this->assertEquals(self::BASEURL. '/api/token', $this->provider->getBaseAccessTokenUrl([]));
    }

    public function testResourceOwnerDetailsUrl(): void
    {
        $this->assertEquals(
            self::BASEURL. '/api/account',
            $this->provider->getResourceOwnerDetailsUrl(new AccessToken([
                'access_token' => 'token'
            ]))
        );
    }

    public function testDefaultScopesAndScopesSeparator(): void
    {
        $scopeSeparator = ' ';
        $options = ['scope' => []];
        $query = ['scope' => implode($scopeSeparator, $options['scope'])];
        $url = $this->provider->getAuthorizationUrl($options);
        $encodedScope = $this->buildQueryString($query);

        $this->assertStringContainsString($encodedScope, $url);
    }

    public function testGetAccessTokenWithAuthorizationCode(): void
    {
        $created_at = date_timestamp_get(date_create());
        $json = <<<JSON
            {
              "access_token": "mock_access_token",
              "token_type": "bearer",
              "refresh_token": "mock_refresh_token",
              "expires_in": 7200,
              "scope": "public",
              "created_at": $created_at
            }
        JSON;
        $response = $this->createMockResponse( $json );

        $client = $this->createMock(ClientInterface::class);
        $client->method('send')->willReturn($response);
        $this->provider->setHttpClient($client);

        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertEquals('mock_refresh_token', $token->getRefreshToken());
        $this->assertLessThanOrEqual(time() + 7200, $token->getExpires());
        $this->assertGreaterThanOrEqual(time(), $token->getExpires());
    }

    public function testGetResourceOwner(): void
    {
        $userData = [
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

        $response = $this->createMockResponse((string) json_encode($userData));

        $client = $this->createMock(ClientInterface::class);
        $client->method('send')->willReturn($response);
        $this->provider->setHttpClient($client);

        $accessToken = $this->createMock(AccessToken::class);
        $accessToken->method('getToken')->willReturn('mock_access_token');

        $resourceOwner = $this->provider->getResourceOwner($accessToken);

        $this->assertInstanceOf(User::class, $resourceOwner);
        $this->assertEquals($userData, $resourceOwner->toArray());
    }

    /**
     * @expectedException League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function testExceptionThrownWhenErrorObjectReceived()
    {
        $status = rand(400,600);
        $error = [
            'error' => uniqid(),
            'error_description' => uniqid(),
            'message' => uniqid(),
        ];
        $response = $this->createMockResponse((string) json_encode($error), $status);

        $client = $this->createMock(ClientInterface::class);
        $client->method('send')->willReturn($response);
        $this->provider->setHttpClient($client);

        $this->expectException(IdentityProviderException::class);
        $this->expectExceptionMessage("{$status } - {$error['error']}: {$error['error_description']}");

        $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
    }
}
