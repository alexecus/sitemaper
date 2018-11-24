<?php

namespace Alexecus\Sitemaper\Test\Writer;

use PHPUnit\Framework\TestCase;
use Alexecus\Sitemaper\Transformer\XmlTransformer;

class XmlTransformerTest extends TestCase
{
    public function testSetTransformSimpleItem()
    {
        $transformer = new XmlTransformer();

        $items = [
            [
                'loc' => 'http://domain.com/',
                'lastmod' => '2005-05-15',
                'changefreq' => 'monthly',
                'priority' => '1.0',
            ],
            [
                'loc' => 'http://domain.com/page',
                'lastmod' => '2005-05-15',
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
        ];


        $xml = $transformer->transform($items);

        $expected = '
            <?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
                <url>
                    <loc>http://domain.com/</loc>
                    <lastmod>2005-05-15</lastmod>
                    <changefreq>monthly</changefreq>
                    <priority>1.0</priority>
                </url>
                <url>
                    <loc>http://domain.com/page</loc>
                    <lastmod>2005-05-15</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>0.8</priority>
                </url>
            </urlset>
        ';

        $expected = trim(
            str_replace(PHP_EOL, '', preg_replace('/\s\s+/', '', $expected))
        );

        $this->assertEquals($expected, str_replace(PHP_EOL, '', $xml));
    }

    public function testSetTransformSimpleWithItemTag()
    {
        $transformer = new XmlTransformer('urlset', 'custom');

        $items = [
            [
                'loc' => 'http://domain.com/',
                'lastmod' => '2005-05-15',
                'changefreq' => 'monthly',
                'priority' => '1.0',
            ],
            [
                'loc' => 'http://domain.com/page',
                'lastmod' => '2005-05-15',
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
        ];


        $xml = $transformer->transform($items);

        $expected = '
            <?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
                <custom>
                    <loc>http://domain.com/</loc>
                    <lastmod>2005-05-15</lastmod>
                    <changefreq>monthly</changefreq>
                    <priority>1.0</priority>
                </custom>
                <custom>
                    <loc>http://domain.com/page</loc>
                    <lastmod>2005-05-15</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>0.8</priority>
                </custom>
            </urlset>
        ';

        $expected = trim(
            str_replace(PHP_EOL, '', preg_replace('/\s\s+/', '', $expected))
        );

        $this->assertEquals($expected, str_replace(PHP_EOL, '', $xml));
    }

    public function testSetTransformSimpleWithBaseTag()
    {
        $transformer = new XmlTransformer('customset');

        $items = [
            [
                'loc' => 'http://domain.com/',
                'lastmod' => '2005-05-15',
                'changefreq' => 'monthly',
                'priority' => '1.0',
            ],
            [
                'loc' => 'http://domain.com/page',
                'lastmod' => '2005-05-15',
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
        ];


        $xml = $transformer->transform($items);

        $expected = '
            <?xml version="1.0" encoding="UTF-8"?>
            <customset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
                <url>
                    <loc>http://domain.com/</loc>
                    <lastmod>2005-05-15</lastmod>
                    <changefreq>monthly</changefreq>
                    <priority>1.0</priority>
                </url>
                <url>
                    <loc>http://domain.com/page</loc>
                    <lastmod>2005-05-15</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>0.8</priority>
                </url>
            </customset>
        ';

        $expected = trim(
            str_replace(PHP_EOL, '', preg_replace('/\s\s+/', '', $expected))
        );

        $this->assertEquals($expected, str_replace(PHP_EOL, '', $xml));
    }

    public function testSetTransformSimpleWithAttributes()
    {
        $transformer = new XmlTransformer('urlset', 'url', [
            'custom' => 'http://www.custom.com/schemas/sitemap/1.0',
        ]);

        $items = [
            [
                'loc' => 'http://domain.com/',
                'lastmod' => '2005-05-15',
                'changefreq' => 'monthly',
                'priority' => '1.0',
            ],
            [
                'loc' => 'http://domain.com/page',
                'lastmod' => '2005-05-15',
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
        ];


        $xml = $transformer->transform($items);

        $expected = '
            <?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" custom="http://www.custom.com/schemas/sitemap/1.0">
                <url>
                    <loc>http://domain.com/</loc>
                    <lastmod>2005-05-15</lastmod>
                    <changefreq>monthly</changefreq>
                    <priority>1.0</priority>
                </url>
                <url>
                    <loc>http://domain.com/page</loc>
                    <lastmod>2005-05-15</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>0.8</priority>
                </url>
            </urlset>
        ';

        $expected = trim(
            str_replace(PHP_EOL, '', preg_replace('/\s\s+/', '', $expected))
        );

        $this->assertEquals($expected, str_replace(PHP_EOL, '', $xml));
    }

    public function testSetTransformSimpleWithAttributesUsingSetter()
    {
        $transformer = (new XmlTransformer())->withAttributes([
            'custom' => 'http://www.custom.com/schemas/sitemap/1.0',
        ]);

        $items = [
            [
                'loc' => 'http://domain.com/',
                'lastmod' => '2005-05-15',
                'changefreq' => 'monthly',
                'priority' => '1.0',
            ],
            [
                'loc' => 'http://domain.com/page',
                'lastmod' => '2005-05-15',
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
        ];


        $xml = $transformer->transform($items);

        $expected = '
            <?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" custom="http://www.custom.com/schemas/sitemap/1.0">
                <url>
                    <loc>http://domain.com/</loc>
                    <lastmod>2005-05-15</lastmod>
                    <changefreq>monthly</changefreq>
                    <priority>1.0</priority>
                </url>
                <url>
                    <loc>http://domain.com/page</loc>
                    <lastmod>2005-05-15</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>0.8</priority>
                </url>
            </urlset>
        ';

        $expected = trim(
            str_replace(PHP_EOL, '', preg_replace('/\s\s+/', '', $expected))
        );

        $this->assertEquals($expected, str_replace(PHP_EOL, '', $xml));
    }

    public function testSetTransformSimpleWithEncoding()
    {
        $transformer = new XmlTransformer('urlset', 'url', [], 'utf-8');

        $items = [
            [
                'loc' => 'http://domain.com/',
                'lastmod' => '2005-05-15',
                'changefreq' => 'monthly',
                'priority' => '1.0',
            ],
            [
                'loc' => 'http://domain.com/page',
                'lastmod' => '2005-05-15',
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
        ];


        $xml = $transformer->transform($items);

        $expected = '
            <?xml version="1.0" encoding="utf-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
                <url>
                    <loc>http://domain.com/</loc>
                    <lastmod>2005-05-15</lastmod>
                    <changefreq>monthly</changefreq>
                    <priority>1.0</priority>
                </url>
                <url>
                    <loc>http://domain.com/page</loc>
                    <lastmod>2005-05-15</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>0.8</priority>
                </url>
            </urlset>
        ';

        $expected = trim(
            str_replace(PHP_EOL, '', preg_replace('/\s\s+/', '', $expected))
        );

        $this->assertEquals($expected, str_replace(PHP_EOL, '', $xml));
    }

    public function testSetTransformSimpleWithGoogleAttributes()
    {
        $transformer = new XmlTransformer();

        $items = [
            [
                'loc' => 'http://domain.com/',
                'lastmod' => '2005-05-15',
                'changefreq' => 'monthly',
                'priority' => '1.0',
                'image' => [
                    'loc' => 'http://domain.com/image.png',
                ],
                'video' => [
                    'title' => 'My Video',
                    'description' => 'This is my video',
                    'content_loc' => 'http://domain.com/video.mp4',
                ],
                'news' => [
                    'publication' => [
                        'name' => 'News Source',
                    ],
                    'title' => 'Hot News',
                    'keywords' => 'sitemap, php',
                ],
            ],
            [
                'loc' => 'http://domain.com/page',
                'lastmod' => '2005-05-15',
                'changefreq' => 'daily',
                'priority' => '0.8',
                'image' => [
                    'loc' => 'http://domain.com/image.png',
                ],
                'video' => [
                    'title' => 'My Video',
                    'description' => 'This is my video',
                    'content_loc' => 'http://domain.com/video.mp4',
                ],
                'news' => [
                    'publication' => [
                        'name' => 'News Source',
                    ],
                    'title' => 'Hot News',
                    'keywords' => 'sitemap, php',
                ],
            ],
        ];

        $xml = $transformer->transform($items);

        $expected = '
            <?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
                <url>
                    <loc>http://domain.com/</loc>
                    <lastmod>2005-05-15</lastmod>
                    <changefreq>monthly</changefreq>
                    <priority>1.0</priority>
                    <image:image>
                        <image:loc>http://domain.com/image.png</image:loc>
                    </image:image>
                    <video:video>
                        <video:title>My Video</video:title>
                        <video:description>This is my video</video:description>
                        <video:content_loc>http://domain.com/video.mp4</video:content_loc>
                    </video:video>
                    <news:news>
                        <news:publication>
                            <news:name>News Source</news:name>
                        </news:publication>
                        <news:title>Hot News</news:title>
                        <news:keywords>sitemap, php</news:keywords>
                    </news:news>
                </url>
                <url>
                    <loc>http://domain.com/page</loc>
                    <lastmod>2005-05-15</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>0.8</priority>
                    <image:image>
                        <image:loc>http://domain.com/image.png</image:loc>
                    </image:image>
                    <video:video>
                        <video:title>My Video</video:title>
                        <video:description>This is my video</video:description>
                        <video:content_loc>http://domain.com/video.mp4</video:content_loc>
                    </video:video>
                    <news:news>
                        <news:publication>
                            <news:name>News Source</news:name>
                        </news:publication>
                        <news:title>Hot News</news:title>
                        <news:keywords>sitemap, php</news:keywords>
                    </news:news>
                </url>
            </urlset>
        ';

        $expected = trim(
            str_replace(PHP_EOL, '', preg_replace('/\s\s+/', '', $expected))
        );

        $this->assertEquals($expected, str_replace(PHP_EOL, '', $xml));
    }

    public function testSetTransformSimpleWithExtension()
    {
        $transformer = new XmlTransformer('urlset', 'url', [], 'UTF-8', [
            'custom' => [
                'prefix' => 'custom',
                'transform' => 'custom:custom',
                'attributes' => [
                    'xmlns:custom' => 'http://www.custom.com/schemas/sitemap-custom/1.0',
                ],
            ],
        ]);

        $items = [
            [
                'loc' => 'http://domain.com/',
                'lastmod' => '2005-05-15',
                'changefreq' => 'monthly',
                'priority' => '1.0',
                'custom' => [
                    'name' => 'This is my custom name',
                ],
            ],
            [
                'loc' => 'http://domain.com/page',
                'lastmod' => '2005-05-15',
                'changefreq' => 'daily',
                'priority' => '0.8',
                'custom' => [
                    'name' => 'This is my custom name',
                ],
            ],
        ];


        $xml = $transformer->transform($items);

        $expected = '
            <?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:custom="http://www.custom.com/schemas/sitemap-custom/1.0">
                <url>
                    <loc>http://domain.com/</loc>
                    <lastmod>2005-05-15</lastmod>
                    <changefreq>monthly</changefreq>
                    <priority>1.0</priority>
                    <custom:custom>
                        <custom:name>This is my custom name</custom:name>
                    </custom:custom>
                </url>
                <url>
                    <loc>http://domain.com/page</loc>
                    <lastmod>2005-05-15</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>0.8</priority>
                    <custom:custom>
                        <custom:name>This is my custom name</custom:name>
                    </custom:custom>
                </url>
            </urlset>
        ';

        $expected = trim(
            str_replace(PHP_EOL, '', preg_replace('/\s\s+/', '', $expected))
        );

        $this->assertEquals($expected, str_replace(PHP_EOL, '', $xml));
    }

    public function testSetTransformSimpleWithExtensionUsingSetter()
    {
        $transformer = (new XmlTransformer())->withExtension([
            'custom' => [
                'prefix' => 'custom',
                'transform' => 'custom:custom',
                'attributes' => [
                    'xmlns:custom' => 'http://www.custom.com/schemas/sitemap-custom/1.0',
                ],
            ],
        ]);

        $items = [
            [
                'loc' => 'http://domain.com/',
                'lastmod' => '2005-05-15',
                'changefreq' => 'monthly',
                'priority' => '1.0',
                'custom' => [
                    'name' => 'This is my custom name',
                ],
            ],
            [
                'loc' => 'http://domain.com/page',
                'lastmod' => '2005-05-15',
                'changefreq' => 'daily',
                'priority' => '0.8',
                'custom' => [
                    'name' => 'This is my custom name',
                ],
            ],
        ];


        $xml = $transformer->transform($items);

        $expected = '
            <?xml version="1.0" encoding="UTF-8"?>
            <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:custom="http://www.custom.com/schemas/sitemap-custom/1.0">
                <url>
                    <loc>http://domain.com/</loc>
                    <lastmod>2005-05-15</lastmod>
                    <changefreq>monthly</changefreq>
                    <priority>1.0</priority>
                    <custom:custom>
                        <custom:name>This is my custom name</custom:name>
                    </custom:custom>
                </url>
                <url>
                    <loc>http://domain.com/page</loc>
                    <lastmod>2005-05-15</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>0.8</priority>
                    <custom:custom>
                        <custom:name>This is my custom name</custom:name>
                    </custom:custom>
                </url>
            </urlset>
        ';

        $expected = trim(
            str_replace(PHP_EOL, '', preg_replace('/\s\s+/', '', $expected))
        );

        $this->assertEquals($expected, str_replace(PHP_EOL, '', $xml));
    }
}
