# URL Validation

[![Build Status](https://travis-ci.org/rogeriopradoj/php-url-validator.svg)](https://travis-ci.org/rogeriopradoj/php-url-validator)

Fork of https://github.com/Fleshgrinder/php-url-validator

I made this repository specifically for Mathias Bynens' little “[in search of the perfect URL validation regex]
(https://mathiasbynens.be/demo/url-regex)” challenge and if I may spoiler you from the beginning, mine is not
perfect. But it is a step closer to it.

The regular expression contains parts from [Diego Perini's regular expression and the comments on his Gist]
(https://gist.github.com/dperini/729294) as well as some stuff from the [Symfony URL constraint pattern]
(https://github.com/symfony/Validator/blob/master/Constraints/UrlValidator.php#L34-L36).

Note that the challenge does not cover all possible valid URL constructs. The unit test contains several URLs which
should be valid and not valid which are not part of the challenge.

Also note that this class is not meant as a real validator, it is more a starting point for a validator. I had to
release the code under the MIT license because it incorporates a big portion of Diego Perini's regular expression—but I
will ask him if it is possible for me to release it under the [Unlicense](http://unlicense.org) license. If you plan to
use this regular expression in your code consider to remove the username, password, port and IP address support; since
such addresses should not be used for e.g. homepages of users on a profile page or within comments on a blog post.

On a last note, the class also contains a scheme (aka protocol) validation regular expression.

The provided unit test has a 100% coverage of the little class and the code is PHP 5.3+ compatible.

## Features
* Full [Internationalized Domain Name (IDN)](https://en.wikipedia.org/wiki/Internationalized_domain_name) support.
* Full support for [Punycode](https://en.wikipedia.org/wiki/Punycode).
* Support for IPv4 and IPv6 addresses as hostname.
* Extraction of URL parts (like [`parse_url`](https://php.net/parse-url)):
  * Scheme (aka protocol)
  * Username
  * Password
  * Hostname
    * Domain + TLD
    * IPv4
    * IPv6
  * Port
  * Path
  * Query
  * Fragment

## Install
The class and tests are available via [composer](https://getcomposer.org/).

```shell
composer require fleshgrinder/url-validator dev-master
```

## TODO
* IPv6 address validation totally relies on [PHP's `filter_var`](https://php.net/filter-var) implementation, find a way 
  to validate it with the regular expression.
* Port the regular expression to JavaScript for usage in HTML input URL elements and of course JavaScript itself.
* Find more funny URLs for the unit test.

## Weblinks
- [Packagist](https://packagist.org/packages/fleshgrinder/url-validator)

## License
> The MIT License (MIT)
>
> For more information, please refer to <https://en.wikipedia.org/wiki/MIT_License>
