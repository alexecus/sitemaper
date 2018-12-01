<p align="center"><img src="assets/banner.png" width="450"></p>

**Sitemaper** is a very flexible PHP sitemap generator library that you can use to 
generate sitemap files. It can generate into various format such as XML.
It can also output the sitemap as raw data so you can bind it to a controller for example.

**View complete documentation on the [official website](https://alexecus.com/project/sitemaper)**

```php
use Alexecus\Sitemaper\Sitemap;

$sitemap = new Sitemap('http://mysite.com');

$sitemap
    ->addItem('/', [
        'lastmod' => '2020-05-15',
        'changefreq' => 'monthly',
        'priority' => '1.0',
    ])
    ->addItem('/page', [
        'lastmod' => '2020-05-15',
        'changefreq' => 'daily',
        'priority' => '0.8',
    ]);

$sitemap->write('sitemap.xml');
```

### Installation

Install via Composer

```bash
$ composer require alexecus/sitemaper
```

### What can Sitemaper do
* Generate XML files 
* Output sitemap XML response from your controller
* Generate sitemap index files

### Why choose Sitemaper
* Probably the most flexible, you can even change the transformers and writers
* Support for Google sitemap extensions
* Support for sitemap index files
* 100% test code coverage
* Fluent usage
* Fully documented
