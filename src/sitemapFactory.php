<?php

namespace Nolikein\SitemapGenerator;

use DateTime;
use DateTimeInterface;

/**
 * The factory create a collection of route which is needed to create a sitemap document.
 * 
 * @method addRoute Add a route to the collection
 * @method getRoutes Gets the route collection
 */
class sitemapFactory
{
    /** @var array|SitemapRoute[] $collection The route "collection" */
    protected $routeCollection = [];

    /**
     * Add a new sitemap route into the "collection"
     * 
     * @param string $url
     * @param DatetimeInterface $lastmodification
     * @param string $frequency
     * @param float $priority
     * 
     * @return self
     */
    public function addRoute(
        string $url,
        DateTimeInterface $lastmodification = null,
        string $frequency = 'always',
        float $priority = 0.5
    ): self {
        if (is_null($lastmodification)) {
            $lastmodification = new Datetime();
        }
        $this->routeCollection[] = ((new SitemapRoute)
            ->setUrl($url)
            ->setLastModification($lastmodification)
            ->setFrequency($frequency)
            ->setPriority($priority));
        return $this;
    }

    /**
     * Return the route "collection"
     * 
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routeCollection;
    }
}
