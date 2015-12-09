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
 * @coversDefaultClass \Fleshgrinder\Validator\URL
 * @usesDefaultClass \Fleshgrinder\Validator\URL
 * @author Diego Perini
 * @author Richard Fussenegger <richard@fussenegger.info>
 * @copyright 2010-2015 Diego Perini & Richard Fussenegger
 * @license MIT
 */
final class URLTest extends \PHPUnit_Framework_TestCase
{


    // ----------------------------------------------------------------------------------------------------------------- Constants


    /**
     * This pattern matches all the test cases from the challenge and was the base for the regular expression I came up
     * with. A few tweaks here and there and it would be as good as my current regular expression is; of course Unicode
     * normalization to NFC is missing (done in PHP) and IPv6 validation (done in PHP) as well.
     *
     * @see https://gist.github.com/dperini/729294
     * @var string
     */
    const DPERINI_PATTERN = '_^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]-*)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]-*)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:/\S*)?$_iuS';


    // ----------------------------------------------------------------------------------------------------------------- Data Provider


    /**
     * @return array
     */
    public function dataProviderInvalidAllowedSchemes()
    {
        return array(
            array(0),
            array(null),
            array(""),
            array(array()),
            array(array(0)),
            array(array(null)),
            array(array("")),
            array(array("scheme~", "http")),
            array(array("http", "42scheme")),
            array(array(42)),
        );
    }

    /**
     * @return array
     */
    public function dataProviderValidURLs()
    {
        return array(
            array("http://foo.com/blah_blah"),
            array("http://foo.com/blah_blah/"),
            array("http://foo.com/blah_blah_(wikipedia)"),
            array("http://foo.com/blah_blah_(wikipedia)_(again)"),
            array("http://www.example.com/wpstyle/?p=364"),
            array("https://www.example.com/foo/?bar=baz&inga=42&quux"),
            array("http://✪df.ws/123"),
            array("http://userid:password@example.com:8080"),
            array("http://userid:password@example.com:8080/"),
            array("http://userid@example.com"),
            array("http://userid@example.com/"),
            array("http://userid@example.com:8080"),
            array("http://userid@example.com:8080/"),
            array("http://userid:password@example.com"),
            array("http://userid:password@example.com/"),
            array("http://142.42.1.1/"),
            array("http://142.42.1.1:8080/"),
            array("http://➡.ws/䨹"),
            array("http://⌘.ws"),
            array("http://⌘.ws/"),
            array("http://foo.com/blah_(wikipedia)#cite-1"),
            array("http://foo.com/blah_(wikipedia)_blah#cite-1"),
            array("http://foo.com/unicode_(✪)_in_parens"),
            array("http://foo.com/(something)?after=parens"),
            array("http://☺.damowmow.com/"),
            array("http://code.google.com/events/#&product=browser"),
            array("http://j.mp"),
            array("ftp://foo.bar/baz"),
            array("http://foo.bar/?q=Test%20URL-encoded%20stuff"),
            array("http://مثال.إختبار"),
            array("http://xn--mgbh0fb.xn--kgbechtv"),
            array("http://例子.测试"),
            array("http://xn--fsqu00a.xn--0zwm56d"),
            array("http://उदाहरण.परीक्षा"),
            array("http://xn--p1b6ci4b4b3a.xn--11b5bs3a6bxe"),
            array("http://-.~_!$&'()*+,;=:%40:80%2f::::::@example.com"),
            array("http://1337.net"),
            array("http://a.b-c.de"),
            array("http://223.255.255.254"),
            array("http://Ω.com"),
            array("http://xn--bya.com/"),
            array("http://北京大学.中國"),
            array("http://xn--1lq90ic7fzpc.xn--fiqz9s"),
            array("http://xn--oogle-qmc.com"),
            array("http://www.öbb.at/"),
            array("http://www.xn--bb-eka.at"),
            array("http://[FEDC:BA98:7654:3210:FEDC:BA98:7654:3210]:80/index.html"),
            array("http://[1080:0:0:0:8:800:200C:417A]/index.html"),
            array("http://[3ffe:2a00:100:7031::1]"),
            array("http://[1080::8:800:200C:417A]/foo"),
            array("http://[::192.9.5.5]/ipng"),
            array("http://[::FFFF:129.144.52.38]:80/index.html"),
            array("http://[2010:836B:4179::836B:4179]"),
            array("http://a.b--c.de/"),
            array("http://www.foo´bar.com"),
        );
    }

    /**
     * @return array
     */
    public function dataProviderInvalidType()
    {
        return array(
            array(null),
            array(0),
            array(0.0),
            array(""),
            array(true),
            array(false),
            array(array()),
            array((object) array()),
        );
    }

    /**
     * @return array
     */
    public function dataProviderInvalidURLs()
    {
        $c1 = chr(1);
        $c2 = chr(2);
        $c3 = chr(3);
        $c4 = chr(4);
        $c5 = chr(5);
        $c6 = chr(6);
        $c7 = chr(7);
        $c8 = chr(8);
        $c14 = chr(14);
        $c15 = chr(15);
        $c16 = chr(16);
        $c17 = chr(17);
        $c18 = chr(18);
        $c19 = chr(19);
        $c20 = chr(20);
        $c21 = chr(21);
        $c22 = chr(22);
        $c23 = chr(23);
        $c24 = chr(24);
        $c127 = chr(127);

        return array(
            array("http://"),
            array("http://."),
            array("http://.."),
            array("http://../"),
            array("http://?"),
            array("http://??"),
            array("http://??/"),
            array("http://#"),
            array("http://##"),
            array("http://##/"),
            array("http://foo.bar?q=Spaces should be encoded"),
            array("//"),
            array("//a"),
            array("///a"),
            array("///"),
            array("http:///a"),
            array("foo.com"),
            array("rdar://1234"),
            array("h://test"),
            array("http:// shouldfail.com"),
            array(":// should fail"),
            array("http://foo.bar/foo(bar)baz quux"),
            array("ftps://foo.bar/"),
            array("http://-error-.invalid/"),
            array("http://-a.b.co"),
            array("http://a.b-.co"),
            array("http://0.0.0.0"),
            array("http://10.1.1.0"),
            array("http://10.1.1.255"),
            array("http://224.1.1.1"),
            array("http://1.1.1.1.1"),
            array("http://123.123.123"),
            array("http://3628126748"),
            array("http://.www.foo.bar/"),
            array("http://www.foo.bar./"),
            array("http://.www.foo.bar./"),
            array("http://10.1.1.1"),
            array("http://203.0.113.0"),
            array("http://198.51.100.0"),
            array("http://192.0.2.0"),
            array("http://example.com/path\0/to\0/file"),
            array("http://example.com/path{$c1}/to{$c1}/file"),
            array("http://example.com/path{$c2}/to{$c2}/file"),
            array("http://example.com/path{$c3}/to{$c3}/file"),
            array("http://example.com/path{$c4}/to{$c4}/file"),
            array("http://example.com/path{$c5}/to{$c5}/file"),
            array("http://example.com/path{$c6}/to{$c6}/file"),
            array("http://example.com/path{$c7}/to{$c7}/file"),
            array("http://example.com/path{$c8}/to{$c8}/file"),
            array("http://example.com/path\t/to\t/file"),
            array("http://example.com/path\n/to\n/file"),
            array("http://example.com/path\v/to\v/file"),
            array("http://example.com/path\f/to\f/file"),
            array("http://example.com/path\r/to\r/file"),
            array("http://example.com/path{$c14}/to{$c14}/file"),
            array("http://example.com/path{$c15}/to{$c15}/file"),
            array("http://example.com/path{$c16}/to{$c16}/file"),
            array("http://example.com/path{$c17}/to{$c17}/file"),
            array("http://example.com/path{$c18}/to{$c18}/file"),
            array("http://example.com/path{$c19}/to{$c19}/file"),
            array("http://example.com/path{$c20}/to{$c20}/file"),
            array("http://example.com/path{$c21}/to{$c21}/file"),
            array("http://example.com/path{$c22}/to{$c22}/file"),
            array("http://example.com/path{$c23}/to{$c23}/file"),
            array("http://example.com/path{$c24}/to{$c24}/file"),
            array("http://example.com/path{$c127}/to{$c127}/file"),
            array("http://www.foo\0bar.com"),
            array("http://www.foo{$c1}bar.com"),
            array("http://www.foo{$c2}bar.com"),
            array("http://www.foo{$c3}bar.com"),
            array("http://www.foo{$c4}bar.com"),
            array("http://www.foo{$c5}bar.com"),
            array("http://www.foo{$c6}bar.com"),
            array("http://www.foo{$c7}bar.com"),
            array("http://www.foo{$c8}bar.com"),
            array("http://www.foo\tbar.com"),
            array("http://www.foo\nbar.com"),
            array("http://www.foo\vbar.com"),
            array("http://www.foo\fbar.com"),
            array("http://www.foo\rbar.com"),
            array("http://www.foo{$c14}bar.com"),
            array("http://www.foo{$c15}bar.com"),
            array("http://www.foo{$c16}bar.com"),
            array("http://www.foo{$c17}bar.com"),
            array("http://www.foo{$c18}bar.com"),
            array("http://www.foo{$c19}bar.com"),
            array("http://www.foo{$c20}bar.com"),
            array("http://www.foo{$c21}bar.com"),
            array("http://www.foo{$c22}bar.com"),
            array("http://www.foo{$c23}bar.com"),
            array("http://www.foo{$c24}bar.com"),
            array("http://www.foo{$c127}bar.com"),
            array("http://www.foo bar.com"),
            array("http://www.foo!bar.com"),
            array('http://www.foo"bar.com'),
            array("http://www.foo#bar.com"),
            array('http://www.foo$bar.com'),
            array("http://www.foo%bar.com"),
            array("http://www.foo&bar.com"),
            array("http://www.foo'bar.com"),
            array("http://www.foo(bar.com"),
            array("http://www.foo)bar.com"),
            array("http://www.foo*bar.com"),
            array("http://www.foo+bar.com"),
            array("http://www.foo,bar.com"),
            array("http://www.foo:bar.com"),
            array("http://www.foo;bar.com"),
            array("http://www.foo<bar.com"),
            array("http://www.foo=bar.com"),
            array("http://www.foo>bar.com"),
            array("http://www.foo?bar.com"),
            array("http://www.foo[bar.com"),
            array('http://www.foo\bar.com'),
            array("http://www.foo]bar.com"),
            array("http://www.foo^bar.com"),
            array("http://www.foo_bar.com"),
            array("http://www.foo`bar.com"),
            array("http://www.foo{bar.com"),
            array("http://www.foo}bar.com"),
            array("http://www.foo|bar.com"),
            array("http://www.foobár.com"), // Unicode normalization NFD
            // Hyphens on the thrid and fourth position are not allowed because they would collide with Punnycode.
            // http://www.unicode.org/reports/tr46/#Validity_Criteria
            array("http://fo--o.com"),
        );
    }


    // ----------------------------------------------------------------------------------------------------------------- Tests


    /**
     * @covers ::__construct
     * @covers ::setAllowedSchemes
     */
    public function testValidAllowedSchemes()
    {
        $allowedSchemes = array("http", "https", "ftp", "ftps", "file");
        $url = new URL(null, $allowedSchemes);
        $property = new \ReflectionProperty($url, "allowedSchemes");
        $property->setAccessible(true);
        $this->assertEquals($allowedSchemes, $property->getValue($url));
    }

    /**
     * @covers ::setAllowedSchemes
     * @uses ::__construct
     * @dataProvider dataProviderInvalidAllowedSchemes
     * @expectedException \InvalidArgumentException
     * @param mixed $allowedSchemes
     */
    public function testSetAllowedSchemes($allowedSchemes)
    {
        $url = new URL();
        $url->setAllowedSchemes($allowedSchemes);
    }

    /**
     * @covers ::__construct
     * @covers ::reset
     * @covers ::validate
     */
    public function testPropertyExport()
    {
        $url = new URL("https://richard:42secret@www2.example.com:8080/path/to/file?key=value;#fragment42");
        $this->assertEquals("https", $url->scheme);
        $this->assertEquals("richard", $url->username);
        $this->assertEquals("42secret", $url->password);
        $this->assertEquals("www2.example.com", $url->hostname);
        $this->assertEquals("www2.example", $url->domain);
        $this->assertEquals("com" ,$url->tld);
        $this->assertEquals(8080, $url->port);
        $this->assertEquals("/path/to/file", $url->path);
        $this->assertEquals("key=value;", $url->query);
        $this->assertEquals("fragment42", $url->fragment);
        $this->assertNull($url->ipv4);
        $this->assertNull($url->ipv6);
    }

    /**
     * @covers ::__construct
     * @covers ::reset
     * @covers ::validate
     */
    public function testPropertyExportIPv4()
    {
        $url = new URL("https://richard:42secret@142.42.1.1:8080/path/to/file?key=value;#fragment42");
        $this->assertEquals("https", $url->scheme);
        $this->assertEquals("richard", $url->username);
        $this->assertEquals("42secret", $url->password);
        $this->assertEquals("142.42.1.1", $url->hostname);
        $this->assertEquals("142.42.1.1", $url->ipv4);
        $this->assertEquals(8080, $url->port);
        $this->assertEquals("/path/to/file", $url->path);
        $this->assertEquals("key=value;", $url->query);
        $this->assertEquals("fragment42", $url->fragment);
        $this->assertNull($url->domain);
        $this->assertNull($url->ipv6);
        $this->assertNull($url->tld);
    }

    /**
     * @covers ::__construct
     * @covers ::reset
     * @covers ::validate
     */
    public function testPropertyExportIPv6()
    {
        $url = new URL("https://richard:42secret@[2010:836B:4179::836B:4179]:8080/path/to/file?key=value;#fragment42");
        $this->assertEquals("https", $url->scheme);
        $this->assertEquals("richard", $url->username);
        $this->assertEquals("42secret", $url->password);
        $this->assertEquals("[2010:836B:4179::836B:4179]", $url->hostname);
        $this->assertEquals("[2010:836B:4179::836B:4179]", $url->ipv6);
        $this->assertEquals(8080, $url->port);
        $this->assertEquals("/path/to/file", $url->path);
        $this->assertEquals("key=value;", $url->query);
        $this->assertEquals("fragment42", $url->fragment);
        $this->assertNull($url->domain);
        $this->assertNull($url->ipv4);
        $this->assertNull($url->tld);
    }

    /**
     * @covers ::__construct
     * @covers ::__toString
     * @covers ::reset
     * @covers ::validate
     * @dataProvider dataProviderValidURLs
     * @param string $url
     */
    public function testValidURLs($url)
    {
        $instance = new URL($url);
        $this->assertEquals($url, $instance->__toString());
    }

    /**
     * @covers ::__construct
     * @covers ::reset
     * @covers ::validate
     * @dataProvider dataProviderInvalidType
     * @dataProvider dataProviderInvalidURLs
     * @expectedException \InvalidArgumentException
     * @param string $url
     */
    public function testInvalidURLs($url)
    {
        $instance = new URL();
        $instance->validate($url);
        // The following will only execute if no exception was thrown; otherwise we will not know which URL it was.
        $this->assertTrue(false, $url);
    }

    /**
     * Note that this test is not meant to illustrate that his regular expression is not good, it is to illustrate that
     * the challenge does not cover all possible URL constructs.
     *
     * @coversNothing
     * @dataProvider dataProviderValidURLs
     * @param string $url
     */
    public function testValidURLsWithDiegoPerinisRegularExpression($url)
    {
        $this->markTestSkipped();
        $this->assertTrue((boolean) preg_match(self::DPERINI_PATTERN, $url), $url);
    }

    /**
     * Note that this test is not meant to illustrate that his regular expression is not good, it is to illustrate that
     * the challenge does not cover all possible URL constructs.
     *
     * @coversNothing
     * @dataProvider dataProviderInvalidURLs
     * @param string $url
     */
    public function testInvalidURLsWithDiegoPerinisRegularExpression($url)
    {
        $this->markTestSkipped();
        $this->assertFalse((boolean) preg_match(self::DPERINI_PATTERN, $url), $url);
    }

}
