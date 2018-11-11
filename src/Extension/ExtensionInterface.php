<?php

namespace Alexecus\Sitemaper\Extension;

use Alexecus\Sitemaper\Sitemap;

interface ExtensionInterface
{
    public function processOptions($options);

    public function processItem($location, $options = []);
}
