<?php

namespace Alexecus\Sitemaper\Writer;

use Alexecus\Sitemaper\Sitemap;

/**
 * Interface to define how sitemap output can be written
 *
 * @author Alex Tenepere <alex.tenepere@gmail.com>
 */
interface WriterInterface
{
    /**
     * Defines how a sitemap output should be written
     *
     * @param string $file The filename of this sitemap
     * @param string $output The sitemap out to be written
     */
    public function write($file, $output);
}
