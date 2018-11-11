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
    ])
    ->addItem('/projects', [
        'priority' => '1.0',
    ])
    ->addItem('/projects/item', [
        'priority' => '1.0',
    ]);

$blogSitemap = new Sitemap('https://blog.alexecus.com/');

$blogSitemap
    ->addItem('/', [
        'lastmod' => '2005-06-10',
        'changefreq' => 'monthly',
        'priority' => '0.8',
    ])
    ->addItem('/blogs', [
        'priority' => '0.8',
    ])
    ->addItem('/blogs/item', [
        'priority' => '0.8',
    ]);

$indexSitemap = new SitemapIndex([
    'sitemap-projects.xml' => new Sitemap('http://alexecus.com', [
        '/projects' => [
            'priority' => '1.0',
        ],
        '/projects/item' => [
            'priority' => '1.0',
        ],
    ]);
]);

echo $projectSitemap->transform('xml');
header('Content-type: application/xml');
die;

// $raw = $projectSitemap->getResponse();
