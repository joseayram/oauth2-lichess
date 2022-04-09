# Lichess OAuth 2.0 provider for the PHP League's OAuth 2.0 Client
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/44bc58692a054dafbe57023440c98882)](https://www.codacy.com/gh/joseayram/oauth2-lichess/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=joseayram/oauth2-lichess&amp;utm_campaign=Badge_Grade)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/joseayram/oauth2-lichess/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/joseayram/oauth2-lichess/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/joseayram/oauth2-lichess/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/joseayram/oauth2-lichess/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/joseayram/oauth2-lichess/badges/build.png?b=main)](https://scrutinizer-ci.com/g/joseayram/oauth2-lichess/build-status/main)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

This package provides [Lichess](https://lichess.org/) OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```bash
$ composer require joseayram/oauth2-lichess
```

## Usage

Usage is just the same as The League's OAuth client, using `\CrudSys\OAuth2\Client\Provider\Lichess` as the provider.

### Authorization Code Flow

```php
require_once 'oauth2-lichess/vendor/autoload.php';

session_start();

use CrudSys\OAuth2\Client\Provider\Lichess;

$clientId = 'api-lichess-test';
$redirectUri = 'https://api.lichess.test/login.php'; // you must change this value

if(!isset($_SESSION['codeVerifier'])) {
    $verifier = \createVerifier();
    $_SESSION['codeVerifier'] = $verifier;
}else{
    $verifier = $_SESSION['codeVerifier'];
}

$challenge = \createChallenge($verifier);

$provider = new Lichess([
    'clientId'      => $clientId,
    'clientSecret'  => 'client-secret',
    'redirectUri'   => $redirectUri
]);

if ( !isset($_GET['code']) && !isset($_GET['error'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl([
        'code_challenge_method' => 'S256',
        'code_challenge' => $challenge,
    ]);

    $_SESSION['oauth2state'] = $provider->getState();

    echo "<a href='{$authUrl}'>Login with Lichess</a>";

} elseif (  ( isset($_GET['error']) && !empty($_GET['error']) ) &&
            ( isset($_GET['error_description']) && !empty($_GET['error_description']) )
) {

    unset($_SESSION['oauth2state']);
    exit($_GET['error'].': '.$_GET['error_description']);

} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state, make sure HTTP sessions are enabled.');
}
else {
    // Try to get an access token (using the authorization coe grant)
    try {
        $token = $provider->getAccessToken('authorization_code', [
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirectUri,
            'client_id' => $clientId,
            'code' => $_GET['code'],
            'code_verifier' => $_SESSION['codeVerifier'],
        ]);
    } catch (\Exception $e) {
        exit('Failed to get access token: '.$e->getMessage());
    }

    // Optional: Now you have a token you can look up a users profile data
    try {
        // We got an access token, let's now get the user's details
        $user = $provider->getResourceOwner($token);
        // Use these details to create a new profile
        printf('Hello %s!\n<br>', $user->getUsername());
        echo "<pre>" . print_r($user, true) . "</pre>";
    } catch (\Exception $e) {
        exit('Failed to get resource owner: '.$e->getMessage());
    }

    // Use this to interact with an API on the users behalf
    echo $token->getToken();
}
```

### Managing Scopes

You can add extra scopes by passing them to the `getAuthorizationUrl()` method

```php
$options = [
    'scope' => [Lichess::SCOPE_EMAIL, Lichess::SCOPE_PREFERENCE_READ]
];

$authorizationUrl = $provider->getAuthorizationUrl($options);
```

If no scopes are passed, only `public` is used

At the time of authoring this documentation, the [following scopes are available](https://lichess.org/api#section/Authentication).

-  `PREFERENCE_READ`  Read your preferences.
-  `PREFERENCE_WRITE` Write your preferences.
-  `EMAIL` Read your email address.
-  `CHALLENGE_READ` Read incoming challenges.
-  `CHALLENGE_WRITE` Create, accept, decline challenges.
-  `CHALLENGE_BULK` Create, delete, query bulk pairings.

## Testing

```bash
$ ./vendor/bin/phpunit
```

## Credits

  - [José Ayram](https://github.com/joseayram)
  - [All Contributors](https://github.com/joseayram/oauth2-lichess/contributors)

## Contributing

Please see [our contributing guidelines](https://github.com/joseayram/oauth2-lichess/blob/master/CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](https://github.com/joseayram/oauth2-lichess/blob/master/LICENSE) for more information.
