<?php

namespace Alexecus\Sitemaper;

use Alexecus\Sitemaper\Transformer\XmlTransformer;
use Alexecus\Sitemaper\Transformer\TransformerInterface;

use Symfony\Component\Filesystem\Filesystem;

class Sitemap
{
    use WriterTrait;

    private $domain;
    private $items = [];
    private $transformers = [];
    private $options = [];

    /**
     *
     */
    public function __construct($domain, $items = [], $options = [])
    {
        $this->domain = $domain;

        foreach ($items as $key => $value) {
            $this->addItem($key, $value);
        }

        $defaultOptions['transformers']['xml'] = new XmlTransformer();
        $this->options = $options + $defaultOptions;

        foreach ($this->options['transformers'] as $key => $value) {
            $this->setTransformer($key, $value);
        }
    }

    /**
     *
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     *
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     *
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     *
     */
    public function setTransformer($id, TransformerInterface $transformer)
    {
        $this->transformers[$id] = $transformer;

        return $this;
    }

    /**
     *
     */
    public function addItem($location, $options = [])
    {
        $domain = rtrim($this->domain, '/');

        $xml['loc'] = $domain . $location;
        $xml += $options;

        $this->items[] = $xml;

        return $this;
    }

    /**
     *
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     *
     */
    public function transform($id)
    {
        $transformer = $this->transformers[$id];

        return $transformer->transform($this->toArray());
    }
}
