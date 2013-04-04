{{COMPONENT}}
=============

Part of the [Moar PHP Library][].

[![Build Status][ci-status]][ci-home]


Installation
------------
{{COMPONENT}} is available on Packagist ([{{PACKAGIST}}][]) and is installable
via [Composer][].

    {
      "require": {
        "moar/metrics": "dev-master"
      }
    }


If you do not use Composer, you can get the source from GitHub and use any
PSR-0 compatible autoloader.

    $ git clone https://github.com/bd808/{{REPO}}.git


Run the tests
-------------
Tests are automatically performed by [Travis CI][]:
[![Build Status][ci-status]][ci-home]


    curl -sS https://getcomposer.org/installer | php
    php composer.phar install --dev
    phpunit


---
[Moar PHP Library]: https://github.com/bd808/moar
[ci-status]: https://travis-ci.org/bd808/{{REPO}}.png
[ci-home]: https://travis-ci.org/bd808/{{REPO}}
[{{PACKAGIST}}]: https://packagist.org/packages/moar/metrics
[Composer]: http://getcomposer.org
[Travis CI]: https://travis-ci.org
