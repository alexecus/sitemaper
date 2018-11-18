<?php

namespace Alexecus\Sitemaper\Transformer;

use Symfony\Component\Serializer\Encoder\XmlEncoder;

/**
 * Class for transforming sitemaps to XML
 *
 * @author Alex Tenepere <alex.tenepere@gmail.com>
 */
class XmlTransformer implements TransformerInterface
{
    /**
     * The base tag for the XML document
     *
     * @var string
     */
    private $basetag = 'urlset';

    /**
     * The item tag for the XML items used for the XML document
     *
     * @var string
     */
    private $itemtag = 'url';

    /**
     * Default attributes for the XML document
     *
     * @var array
     */
    private $attributes = [
        'xmlns' => 'http://www.sitemaps.org/schemas/sitemap/0.9',
    ];

    /**
     * The default encoding attribute for the XML document
     *
     * @var string
     */
    private $encoding = 'UTF-8';

    /**
     * Allows extension of sitemap entries
     *
     * First level key will serve as the key you put in items
     *  string'prefix' If specified will prefix all keys with the given string string:subkey
     *  string 'transform' If specified will transform the original key
     *  array 'attributes' Specify an array of attributes to be put on the XML
     *
     * @var array
     */
    private $extensions = [
        'image' => [
            'prefix' => 'image',
            'transform' => 'image:image',
            'attributes' => [
                'xmlns:image' => 'http://www.google.com/schemas/sitemap-image/1.1',
            ],
        ],

        'video' => [
            'prefix' => 'video',
            'transform' => 'video:video',
            'attributes' => [
                'xmlns:video' => 'http://www.google.com/schemas/sitemap-video/1.1',
            ],
        ],

        'news' => [
            'prefix' => 'news',
            'transform' => 'news:news',
            'attributes' => [
                'xmlns:news' => 'http://www.google.com/schemas/sitemap-news/0.9',
            ],
        ],
    ];

    /**
     * Public constructor
     *
     * @param string $basetag The XML document root tag
     * @param string $itemtag The tag to be used for each sitemap items
     * @param array $attributes A key value pair of XML attributes
     * @param string $encoding The XML encoding to be used
     * @param array $extensions A key value pair of extension definition
     */
    public function __construct($basetag = 'urlset', $itemtag = 'url', $attributes = [], $encoding = 'UTF-8', $extensions = [])
    {
        $this->basetag = $basetag;
        $this->itemtag = $itemtag;
        $this->attributes = array_replace($this->attributes, $attributes);
        $this->encoding = $encoding;
        $this->extensions = array_replace($this->extensions, $attributes);
    }

    /**
     * @{inheritdoc}
     */
    public function transform(array $items)
    {
        $data = [];
        $encoder = new XmlEncoder($this->basetag);

        $extensions = array_keys($this->extensions);

        foreach ($items as $item) {
            $item = $this->extendItem($item, $extensions);

            $data[$this->itemtag][] = $item;
        }

        foreach ($this->attributes as $key => $value) {
            $data['@' . $key] = $value;
        }

        return $encoder->encode($data, 'xml', [
            'xml_encoding' => $this->encoding,
        ]);
    }

    private function extendItem($item, $extensions)
    {
        $result = [];

        foreach ($item as $key => $value) {
            if (isset($this->extensions[$key])) {
                $extension = $this->extensions[$key];

                if (isset($extension['attributes'])) {
                    $this->attributes = array_replace($this->attributes, $extension['attributes']);
                }   

                if (isset($extension['transform'])) {
                    $key = $extension['transform'];
                }

                if (isset($extension['prefix'])) {
                    $value = $this->prefixKeys($extension['prefix'], $value);
                }
            }

            $result[$key] = $value;
        }

        return $result;
    }

    private function prefixKeys($prefix, $values)
    {
        $result = [];

        foreach ($values as $key => $value) {
            $result[$prefix . ':' . $key] = $value;
        }

        return $result;
    }
}
