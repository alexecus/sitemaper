<?php

namespace Alexecus\Sitemaper;

use Symfony\Component\Filesystem\Filesystem;
use Alexecus\Sitemaper\Transformer\XmlTransformer;
use Alexecus\Sitemaper\Transformer\TransformerInterface;
use Alexecus\Sitemaper\Writer\FileWriter;
use Alexecus\Sitemaper\Writer\WriterInterface;

/**
 * Sitemaper sitemap index instances
 *
 * @author Alex Tenepere <alex.tenepere@gmail.com>
 */
class SitemapIndex
{
    private $domain;
    private $filename;
    private $sitemaps = [];
    private $transformers = [];
    private $writer;
    private $options = [];

    /**
     * Public constructor
     *
     * @param string $domain The domain that this sitemap is bounded to
     * @param Sitemap[] $sitemaps Optional array of sitemap items
     * @param string $filename The filename of this sitemap index file
     * @param array $options Options to extend or modify sitemap behaviors
     *
     * Available options:
     *  TransformerInterface[] 'transformers' Define an assoc array of transformers
     *  WriterInterface 'writer' Defines a writer class to be used
     */
    public function __construct($domain, $sitemaps = [], $filename = 'sitemap-index.xml', $options = [])
    {
        $this->domain = $domain;
        $this->filename = $filename;
        $this->sitemaps = $sitemaps;

        $defaultOptions['transformers']['xml'] = new XmlTransformer('sitemapindex', 'sitemap');
        $defaultOptions['writer'] = new FileWriter();

        $this->options = $options + $defaultOptions;

        foreach ($this->options['transformers'] as $key => $value) {
            $this->setTransformer($key, $value);
        }

        $this->setWriter($this->options['writer']);
    }

    /**
     * Sitemap Methods
     *
     */

    /**
     * Adds a sitemap instance for this sitemap index
     *
     * @param Sitemap $sitemap
     * @param string $filename The filename to be used for this sitemap instance
     */
    public function addSitemap(Sitemap $sitemap, $filename = NULL)
    {
        if (empty($filename)) {
            $count = count($this->sitemaps) + 1;
            $filename = "sitemap-$count.xml";
        }

        $this->sitemaps[$filename] = $sitemap;

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

            return $transformer->transform($this->getSitemapIndexArray());
        }

        throw new \InvalidArgumentException("Invalid transformer with ID of $id");
    }

    /**
     * Get Data Methods
     *
     */

    /**
     * Converts the entire sitemap instance to a data array
     * 
     * @return array
     */
    public function toArray()
    {
        $result = [];

        foreach ($this->sitemaps as $filename => $sitemap) {
            $result['sitemaps'][$filename] = $sitemap->toArray();
        }

        $result['index'] = $this->getSitemapIndexArray();

        return $result;
    }

    /**
     * Converts the sitemap index to a data array
     * 
     * @return array
     */
    private function getSitemapIndexArray()
    {
        $items = [];

        foreach ($this->sitemaps as $filename => $sitemap) {
            $items[] = [
                'loc' => rtrim($this->domain, '/') . '/' . $filename,
            ];
        }

        return $items;
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
     * @param string $directory The directory to write the set of sitemaps
     * @param string $format The transfomer ID to be used
     */
    public function write($directory, $format = 'xml')
    {
        $result = [];

        $directory = rtrim($directory, '/');

        foreach ($this->sitemaps as $filename => $sitemap) {
            $sitemap->write($directory . '/' . $filename, $format);
        }

        $this->writeIndex($directory . '/' . $this->filename, $format);
    }

    /**
     * Invokes a write operation but for the sitemap index
     *
     * @param string $file The complete filepath on where to write the sitemap index output
     * @param string $format The transfomer ID to be used
     */
    public function writeIndex($file, $format)
    {
        $this->writer->write($file, $this->transform($format));
    }
}
