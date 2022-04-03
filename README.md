# Lichess OAuth 2.0 provider for the PHP League's OAuth 2.0 Client
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/joseayram/oauth2-lichess/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/joseayram/oauth2-lichess/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/joseayram/oauth2-lichess/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/joseayram/oauth2-lichess/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/joseayram/oauth2-lichess/badges/build.png?b=main)](https://scrutinizer-ci.com/g/joseayram/oauth2-lichess/build-status/main)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

This package provides [Lichess](https://lichess.org/) OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require joseayram/oauth2-lichess
```

## Usage

Usage is just the same as The League's OAuth client, using `\CrudSys\OAuth2\Client\Provider\Lichess` as the provider.

## Testing

```
$ ./vendor/bin/phpunit
```
