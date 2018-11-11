<?php

namespace Alexecus\Sitemaper\Transformer;

use Alexecus\Sitemaper\Sitemap;

interface TransformerInterface
{
    /**
     * Defines how a sitemap should be transformed
     *
     * @param Sitemap $sitemap
     *
     * @return string
     */
    public function transform(Sitemap $sitemap);
}
