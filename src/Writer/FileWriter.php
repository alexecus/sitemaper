<?php

namespace Alexecus\Sitemaper\Writer;

use Symfony\Component\Filesystem\Filesystem;
use Alexecus\Sitemaper\Sitemap;

/**
 * Interface to define how sitemap output can be written
 *
 * @author Alex Tenepere <alex.tenepere@gmail.com>
 */
class FileWriter implements WriterInterface
{
    private $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }
    
    /**
     * {@inheritdoc}
     */
    public function write($file, $output)
    {
        $this->filesystem->dumpFile($file, $output);
    }
}
