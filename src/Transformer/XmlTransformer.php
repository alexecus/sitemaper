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
    public function transform(array $items)
    {
        $data = [];
        $encoder = new XmlEncoder('urlset');

        $data['@xlmns'] = 'http://www.sitemaps.org/schemas/sitemap/0.9';

        foreach ($items as $item) {
            $data['url'][] = $item;
        }

        return $encoder->encode($data, 'xml', [
            'xml_encoding' => $this->xmlEncoding,
        ]);
    }
}
