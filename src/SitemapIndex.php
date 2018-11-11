<?php

namespace Alexecus\Sitemaper;

class SitemapIndex
{
    private $sitemaps = [];
    private $options = [];

    /**
     *
     */
    public function __construct($sitemaps = [], $options = [])
    {
        $this->sitemaps = $sitemaps;
        $this->options = $options;
    }

    /**
     *
     */
    public function addSitemap(Sitemap $sitemap, $filename = NULL)
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
        $children = [];

        foreach ($this->sitemaps as $filename => $sitemap) {
            $children[$filename] = $sitemap->write('xml');
        }
    }
}
