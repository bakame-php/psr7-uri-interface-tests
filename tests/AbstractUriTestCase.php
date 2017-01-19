<?php

namespace Bakame\Psr7\UriTest;

use Psr\Http\Message\UriInterface;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

abstract class AbstractUriTestCase extends TestCase
{
    /**
     * @var UriInterface
     */
    protected $uri;

    protected $uri_string = 'http://login:pass@secure.example.com:443/test/query.php?kingkong=toto#doc3';

    /**
     * UriInterface factory
     *
     * @param string $uri
     *
     * @return UriInterface
     */
    abstract protected function createUri($uri = '');

    protected function setUp()
    {
        $this->uri = $this->createUri($this->uri_string);
    }

    protected function tearDown()
    {
        $this->uri = null;
    }

    /**
     * @group scheme
     * @dataProvider schemeProvider
     *
     * The value returned MUST be normalized to lowercase, per RFC 3986
     * Section 3.1.
     */
    public function testGetScheme($scheme, $expected)
    {
        $uri = $this->uri->withScheme($scheme);
        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertSame($expected, $uri->getScheme(), 'Scheme must be normalized according to RFC3986');
    }

    public function schemeProvider()
    {
        return [
            'normalized scheme' => ['HtTpS', 'https'],
            'simple scheme'     => ['http', 'http'],
            'no scheme'         => ['',''],
        ];
    }

    /**
     * @group userinfo
     * @dataProvider userInfoProvider
     *
     * If a user is present in the URI, this will return that value;
     * additionally, if the password is also present, it will be appended to the
     * user value, with a colon (":") separating the values.
     *
     */
    public function testGetUserInfo($user, $pass, $expected)
    {
        $uri = $this->uri->withUserInfo($user, $pass);
        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertSame($expected, $uri->getUserInfo(), 'UserInfo must be normalized according to RFC3986');
    }

    public function userInfoProvider()
    {
        return [
            'with userinfo'  => ['iGoR', 'rAsMuZeN', 'iGoR:rAsMuZeN'],
            'no userinfo'    => ['', '', ''],
            'no pass'        => ['iGoR', '', 'iGoR'],
            'pass is null'   => ['iGoR', null, 'iGoR'],
            'case sensitive' => ['IgOr', 'RaSm0537', 'IgOr:RaSm0537'],
        ];
    }

    /**
     * @group host
     * @dataProvider hostProvider
     *
     * The value returned MUST be normalized to lowercase, per RFC 3986
     * Section 3.2.2.
     *
     */
    public function testGetHost($host, $expected)
    {
        $uri = $this->uri->withHost($host);
        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertSame($expected, $uri->getHost(), 'Host must be normalized according to RFC3986');
    }

    public function hostProvider()
    {
        return [
            'normalized host' => ["MaStEr.eXaMpLe.CoM", "master.example.com"],
            "simple host"     => ["www.example.com", "www.example.com"],
            "IPv6 Host"       => ["[::1]", "[::1]"],
        ];
    }

    /**
     * @group port
     * @dataProvider portProvider
     *
     * If a port is present, and it is non-standard for the current scheme,
     * this method MUST return it as an integer. If the port is the standard port
     * used with the current scheme, this method SHOULD return null.
     *
     * If no port is present, and no scheme is present, this method MUST return
     * a null value.
     *
     * If no port is present, but a scheme is present, this method MAY return
     * the standard port for that scheme, but SHOULD return null.
     */
    public function testPort($uri, $port, $expected)
    {
        $uri = $this->createUri($uri)->withPort($port);
        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertSame($expected, $uri->getPort(), 'port must be an int or null');
    }

    public function portProvider()
    {
        return [
            'non standard port for http' => ['http://www.example.com', 443, 443],
            'remove port' => ['http://www.example.com', null, null],
            'standard port on schemeless http url' => ['//www.example.com', 80, 80],
        ];
    }

    /**
     * @group port
     */
    public function testUriWithStandardPort($uri, $port)
    {
        $uri = $this->createUri('http://example.com:80');
        $this->assertContains($uri->getPort(), [80, null], "If no port is present, but a scheme is present, this method MAY return the standard port for that scheme, but SHOULD return null.");
    }


    /**
     * @group authority
     * @dataProvider authorityProvider
     *
     * If the port component is not set or is the standard port for the current
     * scheme, it SHOULD NOT be included.
     */
    public function testGetAuthority($scheme, $user, $pass, $host, $port, $authority)
    {
        $uri = $this
                ->createUri()
                ->withHost($host)
                ->withScheme($scheme)
                ->withUserInfo($user, $pass)
                ->withPort($port);
        $this->assertSame($authority, $uri->getAuthority());
    }

    public function authorityProvider()
    {
        return [
            'authority' => [
                'scheme'    => 'http',
                'user'      => 'iGoR',
                'pass'      => 'rAsMuZeN',
                'host'      => 'master.example.com',
                'port'      => 443,
                'authority' => 'iGoR:rAsMuZeN@master.example.com:443',
            ],
            'without port' => [
                'scheme'    => 'http',
                'user'      => 'iGoR',
                'pass'      => 'rAsMuZeN',
                'host'      => 'master.example.com',
                'port'      => null,
                'authority' => 'iGoR:rAsMuZeN@master.example.com',
            ],
            'with standard port' => [
                'scheme'    => 'http',
                'user'      => 'iGoR',
                'pass'      => 'rAsMuZeN',
                'host'      => 'master.example.com',
                'port'      => 80,
                'authority' => 'iGoR:rAsMuZeN@master.example.com',
            ],
            "authority without pass" => [
                'scheme'    => 'http',
                'user'      => 'iGoR',
                'pass'      => '',
                'host'      => 'master.example.com',
                'port'      => null,
                'authority' => 'iGoR@master.example.com',
            ],
            "authority without port and userinfo" => [
                'scheme'    => 'http',
                'user'      => '',
                'pass'      => '',
                'host'      => 'master.example.com',
                'port'      => null,
                'authority' => 'master.example.com',
            ],
        ];
    }

    /**
     * @group query
     * @dataProvider queryProvider
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.4.
     */
    public function testGetQuery($query, $expected)
    {
        $uri = $this->uri->withQuery($query);
        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertSame($expected, $uri->getQuery(), 'Query must be normalized according to RFC3986');
    }

    public function queryProvider()
    {
        return [
            'normalized query' => ['foo.bar=%7evalue', 'foo.bar=%7evalue'],
            'empty query'      => ['', ''],
            'same param query' => ['foo.bar=1&foo.bar=1', 'foo.bar=1&foo.bar=1'],
            'same param query' => ['?foo=1', '?foo=1'],
        ];
    }

    /**
     * @group fragment
     * @dataProvider fragmentProvider
     *
     * The value returned MUST be percent-encoded, but MUST NOT double-encode
     * any characters. To determine what characters to encode, please refer to
     * RFC 3986, Sections 2 and 3.5.
     */
    public function testGetFragment($fragment, $expected)
    {
        $uri = $this->uri->withFragment($fragment);
        $this->assertInstanceOf(UriInterface::class, $uri);
        $this->assertSame($expected, $uri->getFragment(), 'Fragment must be normalized according to RFC3986');
    }

    public function fragmentProvider()
    {
        return [
            'URL with full components'        => ['fragment', 'fragment'],
            'URL with non-encodable fragment' => ["azAZ0-9/?-._~!$&'()*+,;=:@", "azAZ0-9/?-._~!$&'()*+,;=:@"],
        ];
    }

    /**
     * @group uri
     * @dataProvider stringProvider
     *
     * - If a scheme is present, it MUST be suffixed by ":".
     * - If an authority is present, it MUST be prefixed by "//".
     * - The path can be concatenated without delimiters. But there are two
     *   cases where the path has to be adjusted to make the URI reference
     *   valid as PHP does not allow to throw an exception in __toString():
     *     - If the path is rootless and an authority is present, the path MUST
     *       be prefixed by "/".
     *     - If the path is starting with more than one "/" and no authority is
     *       present, the starting slashes MUST be reduced to one.
     * - If a query is present, it MUST be prefixed by "?".
     * - If a fragment is present, it MUST be prefixed by "#".
     */
    public function testToString($scheme, $user, $pass, $host, $port, $path, $query, $fragment, $expected)
    {
        $uri = $this->createUri()
                ->withHost($host)
                ->withScheme($scheme)
                ->withUserInfo($user, $pass)
                ->withPort($port)
                ->withPath($path)
                ->withQuery($query)
                ->withFragment($fragment);
        $this->assertSame($expected, (string) $uri, 'URI string must be normalized according to RFC3986 rules');
    }
    public function stringProvider()
    {
        return [
            'URL normalized' => [
                'scheme'   => 'HtTps',
                'user'     => 'iGoR',
                'pass'     => 'rAsMuZeN',
                'host'     => 'MaStEr.eXaMpLe.CoM',
                'port'     => 443,
                'path'     => '/%7ejohndoe/%a1/index.php',
                'query'    => 'foo.bar=%7evalue',
                'fragment' => 'fragment',
                'uri'      => 'https://iGoR:rAsMuZeN@master.example.com/%7ejohndoe/%a1/index.php?foo.bar=%7evalue#fragment'
            ],
            'URL without scheme' => [
                'scheme'   => '',
                'user'     => '',
                'pass'     => '',
                'host'     => 'www.example.com',
                'port'     => 443,
                'path'     => '/foo/bar',
                'query'    => 'param=value',
                'fragment' => 'fragment',
                'uri'      => '//www.example.com:443/foo/bar?param=value#fragment',
            ],
            'URL without authority and scheme' => [
                'scheme'   => '',
                'user'     => '',
                'pass'     => '',
                'host'     => '',
                'port'     => null,
                'path'     => 'foo/bar',
                'query'    => '',
                'fragment' => '',
                'uri'      => 'foo/bar',
            ],
        ];
    }

    /**
     * @group fragment
     */
    public function testRemoveFragment()
    {
        $uri = 'http://example.com/path/to/me';
        $this->assertSame($uri, (string) $this->createUri($uri.'#doc')->withFragment(''));
    }

    /**
     * @group query
     */
    public function testRemoveQuery()
    {
        $uri = 'http://example.com/path/to/me';
        $this->assertSame($uri, (string) (string) $this->createUri($uri.'?name=value')->withQuery(''));
    }

    /**
     * @group path
     */
    public function testRemovePath()
    {
        $uri = 'http://example.com';
        $this->assertContains(
            (string) $this->createUri($uri.'/path/to/me')->withPath(''),
            [$uri, $uri.'/']
        );
    }

    /**
     * @group port
     */
    public function testRemovePort()
    {
        $this->assertSame(
            'http://example.com/path/to/me',
            (string) $this->createUri('http://example.com:81/path/to/me')->withPort(null)
        );
    }

    /**
     * @group userinfo
     */
    public function testRemoveUserInfo()
    {
        $this->assertSame(
            'http://example.com/path/to/me',
            (string) $this->createUri('http://user:pass@example.com/path/to/me')->withUserInfo('')
        );
    }

    /**
     * @group scheme
     */
    public function testRemoveScheme()
    {
        $this->assertSame(
            '//example.com/path/to/me',
            (string) $this->createUri('http://example.com/path/to/me')->withScheme('')
        );
    }

    /**
     * @group authority
     */
    public function testRemoveAuthority()
    {
        $uri = 'http://user:login@example.com:82/path?q=v#doc';

        $uri_with_host = $this->createUri($uri)
            ->withScheme('')
            ->withUserInfo('')
            ->withPort(null)
            ->withHost('')
        ;

        $this->assertSame('/path?q=v#doc', (string) $uri_with_host);
    }

    /**
     * @group scheme
     * @dataProvider withSchemeFailedProvider
     */
    public function testWithSchemeFailed($scheme)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->uri->withScheme($scheme);
    }

    public function withSchemeFailedProvider()
    {
        return [
            'invalid char'         => ['in,valid'],
            'integer like string'  => ['123'],
            'unknown scheme'       => ['yolo'],
        ];
    }

    /**
     * @group userinfo
     * @dataProvider withUserInfoFailedProvider
     */
    public function testWithUserInfoFailed($user, $pass)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->uri->withUserInfo($user, $pass);
    }

    public function withUserInfoFailedProvider()
    {
        return [
            'invalid character in user :' => ['igo:r', 'rAsMuZeN'],
            'invalid character in user @' => ['igo@r', 'rAsMuZeN'],
            'invalid character in pass @' => ['iGoR', 'rasmu@sen'],
        ];
    }

    /**
     * @group host
     * @dataProvider withHostFailedProvider
     */
    public function testWithHostFailed($host)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->uri->withHost($host);
    }

    public function withHostFailedProvider()
    {
        return [
            'dot in front'                         => ['.example.com'],
            'hyphen suffix'                        => ['host.com-'],
            'multiple dot'                         => ['.......'],
            'one dot'                              => ['.'],
            'empty label'                          => ['tot.    .coucou.com'],
            'space in the label'                   => ['re view'],
            'underscore in label'                  => ['_bad.host.com'],
            'label too long'                       => [implode('', array_fill(0, 12, 'banana')).'.secure.example.com'],
            'too many labels'                      => [implode('.', array_fill(0, 128, 'a'))],
            'Invalid IPv4 format'                  => ['[127.0.0.1]'],
            'Invalid IPv6 format'                  => ['[[::1]]'],
            'Invalid IPv6 format 2'                => ['[::1'],
            'space character in starting label'    => ['example. com'],
            'invalid character in host label'      => ["examp\0le.com"],
            'invalid IP with scope'                => ['[127.2.0.1%253]'],
            'invalid scope IPv6'                   => ['ab23::1234%251'],
            'invalid scope ID'                     => ['fe80::1234%25?@'],
            'invalid scope ID with utf8 character' => ['fe80::1234%25€'],
        ];
    }

    /**
     * @group path
     */
    public function testWithPathFailedWithInvalidPathRelativeToTheAuthority()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->createUri('http://example.com')->withPath('foo/bar');
    }

    /**
     * @group path
     */
    public function testWithPathFailedWithInvalidChars()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->createUri('http://example.com')->withPath('/?');
    }

    /**
     * @group query
     */
    public function testWithQueryFailedWithInvalidChars()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->createUri('http://example.com')->withQuery('#');
    }

    /**
     * @group port
     */
    public function testModificationFailedWithUnsupportedPort()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->createUri('http://example.com')->withPort(0);
    }

    /**
     * @group host
     */
    public function testModificationFailedWithInvalidHost()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->createUri('http://example.com')->withHost('?');
    }

    /**
     * @group uri
     * @dataProvider invalidURI
     */
    public function testCreateFromInvalidUrlKO($uri)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->createUri($uri);
    }

    public function invalidURI()
    {
        return [
            ['http://user@:80'],
            [':'],
        ];
    }

    /**
     * @group uri
     */
    public function testModificationFailedWithSchemeAndPath()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->createUri('/path')->withScheme('http');
    }

    /**
     * @group uri
     */
    public function testEmptyValueDetection()
    {
        $expected = '//0:0@0/0?0#0';
        $this->assertSame($expected, (string) $this->createUri($expected));
    }

    /**
     * @group path
     * @group uri
     */
    public function testPathDetection()
    {
        $expected = 'foo/bar:';
        $this->assertSame($expected, $this->createUri($expected)->getPath());
    }
}