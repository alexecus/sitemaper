<?php

namespace Alexecus\Sitemaper\Transformer;

use Alexecus\Sitemaper\Sitemap;

/**
 * Interface to define how sitemap array data can be transformed
 *
 * @author Alex Tenepere <alex.tenepere@gmail.com>
 */
interface TransformerInterface
{
    /**
     * Defines how a data of sitemap array should be transformed
     *
     * @return string
     */
    public function transform(array $items);
}
