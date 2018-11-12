<?php

namespace Alexecus\Sitemaper;

use Symfony\Component\Serializer\Encoder\XmlEncoder;

class SitemapIndex
{
    private $basepath;
    private $sitemaps = [];
    private $options = [];

    /**
     *
     */
    public function __construct($basepath, $sitemaps = [], $options = [])
    {
        $this->basepath = $basepath;
        $this->sitemaps = $sitemaps;
        $this->options = $options;
    }

    /**
     *
     */
    public function addSitemap(Sitemap $sitemap, $filename = NULL, $options = [])
    {
        if (empty($filename)) {
            $count = count($this->sitemaps) + 1;
            $filename = "sitemap-$count.xml";
        }

        $this->sitemaps[$filename] = $sitemap;

        return $this;
    }

    /**
     *
     */
    public function setSitemaps($sitemaps)
    {
        $this->sitemaps = $sitemaps;

        return $this;
    }

    /**
     *
     */
    public function getSitemaps()
    {
        return $this->sitemaps;
    }

    /**
     *
     */
    public function write($path)
    {
        $result = [];

        foreach ($this->sitemaps as $filename => $sitemap) {
            $result['children'][$filename] = $sitemap->transform('xml');
        }

        $result['index'] = $this->generateIndex();

        return $result;
    }

    /**
     *
     */
    private function generateIndex()
    {
        $encoder = new XmlEncoder('sitemapindex');

        $items['@xlmns'] = 'http://www.sitemaps.org/schemas/sitemap/0.9';

        foreach ($this->sitemaps as $filename => $sitemap) {
            $items['sitemap'][] = [
                'loc' => rtrim($this->basepath, '/') . $filename,
            ];
        }

        return $encoder->encode($items, 'xml', [
            'xml_encoding' => 'utf-8',
        ]);;
    }
}
