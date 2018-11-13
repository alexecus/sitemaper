<?php

namespace Alexecus\Sitemaper;

use Symfony\Component\Filesystem\Filesystem;

trait WriterTrait
{
    public function write($file, $format)
    {
        $fs = new Filesystem();
        $fs->dumpFile($file, $this->transform($format));
    }
}
