<?php

namespace Alexecus\Sitemaper\Transformer;

use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Alexecus\Sitemaper\Sitemap;

class XmlTransformer implements TransformerInterface
{
    private $xmlEncoding;

    public function __construct($xmlEncoding = 'utf-8')
    {
        $this->xmlEncoding = $xmlEncoding;
    }

    /**
     * @{inheritdoc}
     */
    public function transform(Sitemap $sitemap)
    {
        $encoder = new XmlEncoder('urlset');

        $attributes = [];
        $options = $sitemap->getOptions();

        if (isset($options['attributes'])) {
            foreach ($options['attributes'] as $key => $value) {
                $attributes['@' . $key] = $value;
            }
        }

        $items = $sitemap->getItems();
        $items += $attributes;

        return $encoder->encode($items, 'xml', [
            'xml_encoding' => $this->xmlEncoding,
        ]);
    }
}
