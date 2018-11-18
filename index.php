<?php

use Alexecus\Sitemaper\Sitemap;
use Alexecus\Sitemaper\SitemapIndex;

use Symfony\Component\Serializer\Encoder\XmlEncoder;

require __DIR__ . '/vendor/autoload.php';

$projectSitemap = new Sitemap('https://alexecus.com/');

$projectSitemap
    ->addItem('/', [
        'lastmod' => '2005-06-10',
        'changefreq' => 'monthly',
        'priority' => '1.0',
        // 'image' => [
        //     'loc' => 'http://alexecus.com/image.jpg',
        // ]
    ])
    ->addItem('/projects', [
        'priority' => '1.0',
    ])
    ->addItem('/projects/item', [
        'priority' => '1.0',
    ]);

// d($projectSitemap->transform('xml'));
// die;

$indexSitemap = new SitemapIndex('http://alexecus.com', [
    'sitemap-projects.xml' => new Sitemap('http://alexecus.com', [
        '/projects' => [
            'priority' => '1.0',
        ],
        '/projects/item' => [
            'priority' => '1.0',
        ],
    ]),
    'sitemap-blog.xml' => new Sitemap('http://alexecus.com', [
        '/blog' => [
            'priority' => '1.0',
        ],
        '/blog/item' => [
            'priority' => '1.0',
        ],
    ])
]);

// $x = $indexSitemap->write(__DIR__ . '/public');
$x = $indexSitemap->transform('xml');
d($x);
die;

// echo $projectSitemap->transform('xml');
header('Content-type: application/xml');
die;

// $raw = $projectSitemap->getResponse();
