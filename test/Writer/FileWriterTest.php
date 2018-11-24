<?php

namespace Alexecus\Sitemaper\Test\Writer;

use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;
use Alexecus\Sitemaper\Writer\FileWriter;

class FileWriterTest extends TestCase
{
    private $root;

    public function setUp()
    {
        $this->root = vfsStream::setup();
    }

    public function testWrite()
    {
        $writer = new FileWriter();
        $writer->write($this->root->url() . '/sitemap.xml', 'This is a sitemap XML');

        $this->assertEquals('This is a sitemap XML', $this->root->getChild('sitemap.xml')->getContent());
    }
}
