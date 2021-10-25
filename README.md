# Sitemap Generator

![Gitlab pipeline status](https://img.shields.io/gitlab/pipeline/Come-Wasik/sitemap-generator/master)

## Overview

Generating sitemap.xml file means either writing raw xml or stocking data then loop on it. This generator allows you to generate urls data to show in your file.

## History

I created it when i had to generate a sitemap.xml file from one of my Laravel projects. Originally made to work only with Laravel, I finaly modified it to works on every php projects. In addition, Blade the template engine provided by Laravel take any array as a collection so, i don't have to create any adapted package.

## Installation

```bash
composer require nolikein/sitemap-generator ^1.0.0
```

## Usage

### Example with Php from sracth

```php
use Nolikein\SitemapGenerator\SitemapFactory;

    $generator = new SitemapFactory();
    $generator
        ->addRoute('http://domain.org/', new Datetime('now'), 'always', 1)
        ...
        ;
    $routes = $generator->getRoutes();
```

### Example with Laravel
Here an example with Laravel 8 :

```php
use Nolikein\SitemapGenerator\SitemapFactory;

    $generator = new SitemapFactory();
    $generator
        ->addRoute(route('welcome'), now(), 'always', 1)
        ...
        ;
    return response()
            ->view('frontoffice.sitemap', [
                'routes' => $generator->getRoutes()
            ])
            ->header('Content-Type', 'application/xml');

```

## Showing the result into a sitemap file (blade example)
```php
{{-- @php echo '<?xml version="1.0" encoding="UTF-8"?>' @endphp --}}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($routes as $route)
        <url>
            <loc>{{ $route->getUrl() }}</loc>
            <lastmod>{{ $route->getLastmodification()->format('Y-m-d') }}</lastmod>
            <changefreq>{{ $route->getFrequency() }}</changefreq>
            <priority>{{ $route->getPriority() }}</priority>
        </url>
    @endforeach
</urlset>

```

## Links

+ [Link to packagist](https://packagist.org/packages/nolikein/sitemap-generator)