<?php
namespace Alexecus\Sitemaper\Test;

use PHPUnit\Framework\TestCase;
use Alexecus\Sitemaper\Sitemap;
use Alexecus\Sitemaper\SitemapIndex;
use Alexecus\Sitemaper\Writer\WriterInterface;
use Alexecus\Sitemaper\Transformer\TransformerInterface;

class SitemapIndexTest extends TestCase
{
    private $sitemapItems = [
        '/' => [
            'lastmod' => '2005-05-15',
            'changefreq' => 'monthly',
            'priority' => '1.0',
        ],
        '/page' => [
            'lastmod' => '2005-05-15',
            'changefreq' => 'daily',
            'priority' => '0.8',
        ],
        '/page/item' => [
            'lastmod' => '2005-05-15',
            'changefreq' => 'daily',
            'priority' => '0.8',
        ]
    ];

    private $sitemapIndexArray = [
        'sitemaps' => [
            'sitemap-one.xml' => [
                [
                    'loc' => 'http://one.domain.com/',
                    'lastmod' => '2005-05-15',
                    'changefreq' => 'monthly',
                    'priority' => '1.0',
                ],
                [
                    'loc' => 'http://one.domain.com/page',
                    'lastmod' => '2005-05-15',
                    'changefreq' => 'daily',
                    'priority' => '0.8',
                ],
                [
                    'loc' => 'http://one.domain.com/page/item',
                    'lastmod' => '2005-05-15',
                    'changefreq' => 'daily',
                    'priority' => '0.8',
                ]
            ],
            'sitemap-two.xml' => [
                [
                    'loc' => 'http://two.domain.com/',
                    'lastmod' => '2005-05-15',
                    'changefreq' => 'monthly',
                    'priority' => '1.0',
                ],
                [
                    'loc' => 'http://two.domain.com/page',
                    'lastmod' => '2005-05-15',
                    'changefreq' => 'daily',
                    'priority' => '0.8',
                ],
                [
                    'loc' => 'http://two.domain.com/page/item',
                    'lastmod' => '2005-05-15',
                    'changefreq' => 'daily',
                    'priority' => '0.8',
                ]
            ],
        ],
        'index' => [
            [
                'loc' => 'http://domain.com/sitemap-one.xml'
            ],
            [
                'loc' => 'http://domain.com/sitemap-two.xml'
            ],
        ],
    ];

    public function testSitemapIndexAddItems()
    {
        $sitemapIndex = new SitemapIndex('http://domain.com');

        $sitemapIndex->addSitemap(
            new Sitemap('http://one.domain.com', $this->sitemapItems),
            'sitemap-one.xml'
        );

        $sitemapIndex->addSitemap(
            new Sitemap('http://two.domain.com', $this->sitemapItems),
            'sitemap-two.xml'
        );

        $this->assertEquals($this->sitemapIndexArray, $sitemapIndex->toArray());
    }

    public function testSitemapIndexAddItemsNoFilenames()
    {
        $sitemapIndex = new SitemapIndex('http://domain.com');

        $sitemapIndex->addSitemap(
            new Sitemap('http://one.domain.com', $this->sitemapItems)
        );

        $sitemapIndex->addSitemap(
            new Sitemap('http://two.domain.com', $this->sitemapItems)
        );

        $expected['index'] = [
            [
                'loc' => 'http://domain.com/sitemap-1.xml'
            ],
            [
                'loc' => 'http://domain.com/sitemap-2.xml'
            ],
        ];

        $expected['sitemaps']['sitemap-1.xml'] = $this->sitemapIndexArray['sitemaps']['sitemap-one.xml'];
        $expected['sitemaps']['sitemap-2.xml'] = $this->sitemapIndexArray['sitemaps']['sitemap-two.xml'];

        $this->assertEquals($expected, $sitemapIndex->toArray());
    }

    public function testSitemapIndexAddItemsConstructor()
    {
        $sitemapIndex = new SitemapIndex('http://domain.com', [
            'sitemap-one.xml' => new Sitemap('http://one.domain.com', $this->sitemapItems),
            'sitemap-two.xml' => new Sitemap('http://two.domain.com', $this->sitemapItems),
        ]);

        $this->assertEquals($this->sitemapIndexArray, $sitemapIndex->toArray());
    }

    public function testTransform()
    {
        $transformer = $this->createMock(TransformerInterface::class);

        $transformer->method('transform')
            ->will($this->returnArgument(0));

        $options = [
            'transformers' => [
                'mock' => $transformer
            ],
        ];

        $sitemapIndex = new SitemapIndex(
            'http://domain.com',
            [
                'sitemap-one.xml' => new Sitemap('http://one.domain.com', $this->sitemapItems),
                'sitemap-two.xml' => new Sitemap('http://two.domain.com', $this->sitemapItems),
            ],
            'sitemap-index.xml',
            $options
        );

        $this->assertEquals($this->sitemapIndexArray['index'], $sitemapIndex->transform('mock'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidTransform()
    {
        $sitemapIndex = new SitemapIndex('http://domain.com', [
            'sitemap-one.xml' => new Sitemap('http://one.domain.com', $this->sitemapItems),
            'sitemap-two.xml' => new Sitemap('http://two.domain.com', $this->sitemapItems),
        ]);

        $sitemapIndex->transform('undefined');
    }

    public function testWrite()
    {
        $transformer = $this->createMock(TransformerInterface::class);

        $transformer->method('transform')
            ->will($this->returnArgument(0));

        $fileStash = [];

        $writer = $this->createMock(WriterInterface::class);

        $writer->method('write')
            ->willReturnCallback(function ($file, $output) use (&$fileStash) {
                $fileStash[$file] = $output;
            });

        $options = [
            'transformers' => [
                'mock' => $transformer
            ],
            'writer' => $writer,
        ];

        $sitemapIndex = new SitemapIndex(
            'http://domain.com',
            [
                'sitemap-one.xml' => new Sitemap('http://one.domain.com', $this->sitemapItems, $options),
                'sitemap-two.xml' => new Sitemap('http://two.domain.com', $this->sitemapItems, $options),
            ],
            'sitemap-index.xml',
            $options
        );

        $sitemapIndex->write('public', 'mock');

        $this->assertEquals(
            [
                'public/sitemap-one.xml' => $this->sitemapIndexArray['sitemaps']['sitemap-one.xml'],
                'public/sitemap-two.xml' => $this->sitemapIndexArray['sitemaps']['sitemap-two.xml'],
                'public/sitemap-index.xml' => $this->sitemapIndexArray['index'],
            ],
            $fileStash
        );
    }
}
