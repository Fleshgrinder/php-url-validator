<?php

/*!
 * The MIT License (MIT)
 *
 * Copyright (c) 2010-2015 Diego Perini & Richard Fussenegger
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
 * Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Fleshgrinder\Validator;

/**
 * Defines the URL class.
 *
 * This is not a fully fledged class which one might want to use within a project to represent URLs. It was specifically
 * created for the challenge “In search of the perfect URL validation regex” by Mathias Bynens (see link below). It is
 * up to you what you want to do with this class and the presented regular expressions.
 *
 * @see https://mathiasbynens.be/demo/url-regex
 * @author Diego Perini
 * @author Richard Fussenegger <richard@fussenegger.info>
 * @copyright 2010-2015 Diego Perini & Richard Fussenegger
 * @license MIT
 */
final class URL
{


    // ----------------------------------------------------------------------------------------------------------------- Constants


    /**
     * The regular expression pattern for scheme validation.
     *
     * @see https://tools.ietf.org/html/std66
     * @see https://tools.ietf.org/html/rfc3986
     * @var string
     */
    const SCHEME_PATTERN = '/^[a-z][a-z0-9+\.-]*$/Di';

    /**
     * The regular expression pattern for URL validation.
     *
     * Please note that the IPv6 regular expression is taken from the Symfony project, see link below.
     *
     * @see https://github.com/symfony/Validator/blob/master/Constraints/UrlValidator.php#L34-L36
     * @see https://tools.ietf.org/html/rfc3986
     * @var string
     */
    const URL_PATTERN = '/^
            (?\'scheme\'%s)
            :\/\/
            (?:
                (?\'username\'.+)
                (?::(?\'password\'.+))?
            @)?
            (?\'hostname\'
                (?!\.)
                (?\'domain\'(?:\.?(?:xn--[[:alnum:]-]+|(?!..--)[[:alnum:]\x{00a1}-\x{ffff}]+-*))+)
                (?<!-)
                (?:\.(?\'tld\'(?:[a-z\x{00a1}-\x{ffff}]{2,}|xn--[[:alnum:]-]+)))
                    |
                (?\'ipv4\'
                    (?!(?:10|127)(?:\.\d{1,3}){3})
                    (?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})
                    (?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})
                    (?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])
                    (?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}
                    (?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))
                )
                    |
                (?\'ipv6\'
                    \[
                        (?:(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){6})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:::(?:(?:(?:[0-9a-f]{1,4})):){5})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){4})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,1}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){3})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,2}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){2})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,3}(?:(?:[0-9a-f]{1,4})))?::(?:(?:[0-9a-f]{1,4})):)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,4}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,5}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,6}(?:(?:[0-9a-f]{1,4})))?::))))
                    \]
                )
            )
            (?::(?\'port\'\d+))?
            (?:(?=\/?)
                (?\'path\'\/[^\?\#\s[:cntrl:]]*)?
                (?:\?(?\'query\'[^\#\s[:cntrl:]]*))?
                (?:\#(?\'fragment\'[^\s[:cntrl:]]*))?
            )?
        $/DiuUx';


    // ----------------------------------------------------------------------------------------------------------------- Properties


    /**
     * The schemes which should be considered valid during validation.
     *
     * @var array
     */
    private $allowedSchemes = array("http", "https", "ftp");

    /**
     * The domain of the URL.
     *
     * The domain includes all possible sub-domains but not the top-level domain (TLD).
     *
     * @see URL::$hostname
     * @see URL::$tld
     * @var null|string
     */
    public $domain;

    /**
     * The fragment of the URL.
     *
     * Note that the fragment does not include the separator character (`#`).
     *
     * @var null|string
     */
    public $fragment;

    /**
     * The hostname of the URL.
     *
     * Note that the hostname is either a real domain with top-level domain (TLD) or an IP address.
     *
     * @see URL::$domain
     * @see URL::$ipv4
     * @see URL::$ipv6
     * @see URL::$tld
     * @var null|string
     */
    public $hostname;

    /**
     * The IPv4 address (hostname) of the URL.
     *
     * @var null|string
     */
    public $ipv4;

    /**
     * The IPv6 address (hostname) of the URL.
     *
     * @var null|string
     */
    public $ipv6;

    /**
     * The password of the URL.
     *
     * @var null|string
     */
    public $password;

    /**
     * The path of the URL.
     *
     * @var null|string
     */
    public $path;

    /**
     * The port of the URL.
     *
     * @var null|string
     */
    public $port;

    /**
     * The query of the URL.
     *
     * Note that the query does not include the separator character (`?`).
     *
     * @var null|string
     */
    public $query;

    /**
     * The scheme (aka protocol) of the URL.
     *
     * @var null|string
     */
    public $scheme;

    /**
     * The top-level domain (TLD) of the URL.
     *
     * @var null|string
     */
    public $tld;

    /**
     * The full URL.
     *
     * @var null|string
     */
    private $url;

    /**
     * The username of the URL.
     *
     * @var null|string
     */
    public $username;


    // ----------------------------------------------------------------------------------------------------------------- Magic Methods


    /**
     * Construct new URL instance.
     *
     * @param string $url
     *   {@see URL::validate}
     * @param mixed $allowedSchemes
     *   {@see URL::setAllowedSchemes}
     */
    public function __construct($url = null, $allowedSchemes = null)
    {
        if ($allowedSchemes !== null) {
            $this->setAllowedSchemes($allowedSchemes);
        }
        if ($url !== null) {
            $this->validate($url);
        }
    }

    /**
     * Get the string representation of the URL.
     *
     * @return string
     *   The string representation of the URL.
     */
    public function __toString()
    {
        return (string) $this->url;
    }


    // ----------------------------------------------------------------------------------------------------------------- Methods


    /**
     * Set the allowed schemes.
     *
     * By default `http` and `https` are allowed.
     *
     * @param array|string $allowedSchemes
     *   The schemes to allow.
     * @return $this
     * @throws \InvalidArgumentException
     *   If a scheme is empty or contains illegal characters.
     */
    public function setAllowedSchemes($allowedSchemes)
    {
        if (empty($allowedSchemes)) {
            throw new \InvalidArgumentException("Allowed schemes cannot be empty.");
        }

        $allowedSchemes = (array) $allowedSchemes;
        $c = count($allowedSchemes);
        for ($i = 0; $i < $c; ++$i) {
            if (empty($allowedSchemes[$i])) {
                throw new \InvalidArgumentException("An allowed scheme cannot be empty.");
            } elseif (!preg_match(static::SCHEME_PATTERN, $allowedSchemes[$i])) {
                throw new \InvalidArgumentException("Allowed scheme [{$allowedSchemes[$i]}] contains illegal characters (see RFC3986).");
            }
        }
        $this->allowedSchemes = $allowedSchemes;

        return $this;
    }

    /**
     * Reset all properties to their defaults.
     *
     * @return $this
     */
    public function reset()
    {
        $this->domain   = null;
        $this->fragment = null;
        $this->hostname = null;
        $this->ipv4     = null;
        $this->ipv6     = null;
        $this->password = null;
        $this->path     = null;
        $this->port     = null;
        $this->query    = null;
        $this->scheme   = null;
        $this->tld      = null;
        $this->url      = null;
        $this->username = null;

        return $this;
    }

    /**
     * Validate the URL.
     *
     * The various URL parts are exported to class scope, have a look at the public properties of this class. Note that
     * changing any of the properties does not alter the URL itself which this instance represents.
     *
     * @param string $url
     *   The URL to set.
     * @return $this
     * @throws \InvalidArgumentException
     *   If the URL is empty or invalid.
     */
    public function validate($url)
    {
        $this->reset();

        if ($url === null || $url === "") {
            throw new \InvalidArgumentException("URL cannot be empty.");
        }

        // No need to continue with boolean, float, integer, or what not since they will never contain a valid URL.
        if (!is_string($url) && !(is_object($url) && method_exists($url, "__toString"))) {
            throw new \InvalidArgumentException("URL must be representable as string.");
        }

        // NFC form is a requirement for a valid URL.
        if (strlen($url) !== strlen(utf8_decode($url)) && $url !== \Normalizer::normalize($url, \Normalizer::NFC)) {
            throw new \InvalidArgumentException("URL must be in Unicode normalization form NFC.");
        }

        if (!preg_match(sprintf(static::URL_PATTERN, implode("|", $this->allowedSchemes)), $url, $matches)) {
            throw new \InvalidArgumentException("URL [{$url}] is invalid.");
        }

        foreach ($matches as $property => $value) {
            if (!is_numeric($property) && !empty($value)) {
                $this->{$property} = $value;
            }
        }

        // TODO: Incorporate IPv6 validation into regular expression for JavaScript usage.
        if (isset($this->ipv6) && !filter_var(substr($this->ipv6, 1, -1), FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            // @codeCoverageIgnoreStart
            $e = new \InvalidArgumentException("IPv6 address {$this->ipv6} is invalid.");
            $this->reset();
            throw $e;
            // @codeCoverageIgnoreEnd
        }

        $this->url = $url;

        return $this;
    }

}
