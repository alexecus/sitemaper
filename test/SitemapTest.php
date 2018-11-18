<?php
namespace Alexecus\Sitemaper\Test;

use PHPUnit\Framework\TestCase;
use Alexecus\Sitemaper\Sitemap;
use Alexecus\Sitemaper\Writer\WriterInterface;
use Alexecus\Sitemaper\Transformer\TransformerInterface;

class SitemapTest extends TestCase
{
    private $sitemapArray = [
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
        [
            'loc' => 'http://domain.com/page/item',
            'lastmod' => '2005-05-15',
            'changefreq' => 'daily',
            'priority' => '0.8',
        ]
    ];

    public function testSitemapAddItems()
    {
        $sitemap = new Sitemap('http://domain.com');

        $sitemap
            ->addItem('/', [
                'lastmod' => '2005-05-15',
                'changefreq' => 'monthly',
                'priority' => '1.0',
            ])
            ->addItem('/page', [
                'lastmod' => '2005-05-15',
                'changefreq' => 'daily',
                'priority' => '0.8',
            ])
            ->addItem('/page/item', [
                'lastmod' => '2005-05-15',
                'changefreq' => 'daily',
                'priority' => '0.8',
            ]);

        $this->assertEquals($this->sitemapArray, $sitemap->toArray());
    }

    public function testSitemapAddItemsConstructor()
    {
        $sitemap = new Sitemap('http://domain.com', [
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
        ]);

        $this->assertEquals($this->sitemapArray, $sitemap->toArray());
    }

    public function testTransform()
    {
        $transformer = $this->createMock(TransformerInterface::class);

        $transformer->method('transform')
            ->will($this->returnArgument(0));

        $sitemap = new Sitemap('http://domain.com', [], [
            'transformers' => [
                'mock' => $transformer
            ],
        ]);

        $sitemap
            ->addItem('/', [
                'lastmod' => '2005-05-15',
                'changefreq' => 'monthly',
                'priority' => '1.0',
            ])
            ->addItem('/page', [
                'lastmod' => '2005-05-15',
                'changefreq' => 'daily',
                'priority' => '0.8',
            ])
            ->addItem('/page/item', [
                'lastmod' => '2005-05-15',
                'changefreq' => 'daily',
                'priority' => '0.8',
            ]);

        $this->assertEquals($this->sitemapArray, $sitemap->transform('mock'));
    }

    public function testWrite()
    {
        $transformer = $this->createMock(TransformerInterface::class);

        $transformer->method('transform')
            ->will($this->returnArgument(0));

        $filename = null;
        $data = null;

        $writer = $this->createMock(WriterInterface::class);

        $writer->method('write')
            ->willReturnCallback(function ($file, $output) use (&$filename, &$data) {
                $filename = $file;
                $data = $output;
            });

        $sitemap = new Sitemap('http://domain.com', [], [
            'transformers' => [
                'mock' => $transformer
            ],
            'writer' => $writer,
        ]);

        $sitemap
            ->addItem('/', [
                'lastmod' => '2005-05-15',
                'changefreq' => 'monthly',
                'priority' => '1.0',
            ])
            ->addItem('/page', [
                'lastmod' => '2005-05-15',
                'changefreq' => 'daily',
                'priority' => '0.8',
            ])
            ->addItem('/page/item', [
                'lastmod' => '2005-05-15',
                'changefreq' => 'daily',
                'priority' => '0.8',
            ]);

        $sitemap->write('sitemap.xml', 'mock');

        $this->assertEquals('sitemap.xml', $filename);
        $this->assertEquals($this->sitemapArray, $data);
    }
}
