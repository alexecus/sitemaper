<?php

namespace Alexecus\Sitemaper;

use Symfony\Component\Filesystem\Filesystem;
use Alexecus\Sitemaper\Transformer\XmlTransformer;
use Alexecus\Sitemaper\Transformer\TransformerInterface;
use Alexecus\Sitemaper\Writer\FileWriter;
use Alexecus\Sitemaper\Writer\WriterInterface;

/**
 * Sitemaper sitemap instances
 *
 * @author Alex Tenepere <alex.tenepere@gmail.com>
 */
class Sitemap
{
    private $domain;
    private $items = [];
    private $transformers = [];
    private $writer;
    private $options = [];

    /**
     * Public constructor
     *
     * @param string $domain The domain that this sitemap is bounded to
     * @param array $items Optional array of sitemap items
     * @param array $options Options to extend or modify sitemap behaviors
     *
     * Available options:
     *  TransformerInterface[] 'transformers' Define an assoc array of transformers
     *  WriterInterface 'writer' Defines a writer class to be used
     */
    public function __construct($domain, $items = [], $options = [])
    {
        $this->domain = $domain;

        foreach ($items as $key => $value) {
            $this->addItem($key, $value);
        }

        $defaultOptions['transformers']['xml'] = new XmlTransformer();
        $defaultOptions['writer'] = new FileWriter();

        $this->options = $options + $defaultOptions;

        foreach ($this->options['transformers'] as $key => $value) {
            $this->setTransformer($key, $value);
        }

        $this->setWriter($this->options['writer']);
    }

    /**
     * Sitemap Item Methods
     *
     */

    /**
     * Adds a sitemap item
     *
     * @param string $location The path to this sitemap item
     * @param array $options Provide a key value pair of items that will serve as additional tags for this item
     * 
     * @return self
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
     * Transformer Methods
     *
     */

    /**
     * Sets a transformer class object
     *
     * @param string $id The ID of the new transformer
     * @param TransformerInterface $transformer
     *
     * @return self
     */
    public function setTransformer($id, TransformerInterface $transformer)
    {
        $this->transformers[$id] = $transformer;

        return $this;
    }

    /**
     * Transforms this sitemap instance to new data format using an existing transformer
     *
     * @param string $id The ID of the transformer to use
     */
    public function transform($id)
    {
        if (isset($this->transformers[$id])) {
            $transformer = $this->transformers[$id];

            return $transformer->transform($this->toArray());
        }

        throw new \InvalidArgumentException("Invalid transformer with ID of $id");
    }

    /**
     * Get Data Methods
     *
     */

    /**
     * Converts this sitemap instance to a data array
     * 
     * @return array
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * Writer Methods
     *
     */

    /**
     * Sets a new filesystem writer for this sitemap instance
     * 
     * @param mixed $writer
     */
    public function setWriter(WriterInterface $writer)
    {
        $this->writer = $writer;
    }

    /**
     * Invokes a write operation
     *
     * @param string $file The complete filepath on where to write the sitemap output
     * @param string $format The transfomer ID to be used
     */
    public function write($file, $format = 'xml')
    {
        $this->writer->write($file, $this->transform($format));
    }
}
