<?php

namespace Alexecus\Sitemaper;

use Alexecus\Sitemaper\Transformer\XmlTransformer;
use Alexecus\Sitemaper\Transformer\TransformerInterface;

class Sitemap
{
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

        $defaultOptions['attributes']['xlmns'] = 'http://www.sitemaps.org/schemas/sitemap/0.9';
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
    public function addItem($location, $options = [])
    {
        $domain = rtrim($this->domain, '/');

        $xml['loc'] = $domain . $location;
        $xml += $options;

        $this->items['url'][] = $xml;

        return $this;
    }

    /**
     *
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     *
     */
    public function getItems()
    {
        return $this->items;
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
    public function transform($id)
    {
        $transformer = $this->transformers[$id];

        return $transformer->transform($this);
    }
}
