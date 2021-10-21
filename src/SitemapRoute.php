<?php

namespace Nolikein\SitemapGenerator;

use DateTime;
use DateTimeInterface;
use InvalidArgumentException;

/**
 * This is an entity to encapsulate a sitemap "url" element.
 * There is setters to check if the user send well formed data.
 */
class SitemapRoute
{
    /** @var string $url Url for the route */
    protected string $url;

    /** @var string $lastmodification The last modification for the route */
    protected DateTimeInterface $lastmodification;

    /** @var string $frequency The refresh frequency for the route */
    protected $frequency;

    /** @var float $priority The route priority between 0.0 and 1 */
    protected float $priority;

    /** @var string[] List of accepted frequencies */
    const ACCEPTED_FREQUENCIES = [
        'always',
        'hourly',
        'daily',
        'weekly',
        'monthly',
        'yearly',
        'never'
    ];

    /**
     * Getter - Gets the route url
     * 
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Getter - Gets the route last modification date
     * 
     * @return DateTimeInterface
     */
    public function getLastmodification(): DateTimeInterface
    {
        return $this->lastmodification;
    }

    /**
     * Getter - Gets the route refresh frequency
     * 
     * @return string
     */
    public function getFrequency(): string
    {
        return $this->frequency;
    }

    /**
     * Getter - Gets the route showhed priority
     * 
     * @return float
     */
    public function getPriority(): float
    {
        return $this->priority;
    }

    /**
     * Setter - Sets the route url
     * 
     * @param string $url
     * 
     * @return self
     */
    public function setUrl(string $url): self
    {
        if(filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException($url . ' must be an url');
        }
        $this->url = $url;
        return $this;
    }

    /**
     * Setter - Sets the route last modification date
     * 
     * @param DatetimeInterface $lastmodification
     * 
     * @return self
     */
    public function setLastModification(DateTimeInterface $lastmodification): self
    {
        if($lastmodification > new DateTime('now')) {
            throw new InvalidArgumentException('The lastmodification property must have a datetime equal or less than now');
        }
        $this->lastmodification = $lastmodification;
        return $this;
    }

    /**
     * Setter - Sets the route refresh frequency
     * 
     * @param string $frequency
     * 
     * @return self
     */
    public function setFrequency(string $frequency): self
    {
        if(!in_array($frequency, self::ACCEPTED_FREQUENCIES)) {
            throw new InvalidArgumentException('Frequency ' . $frequency . ' is invalid. Choose one of the following: ' . implode(', ', self::ACCEPTED_FREQUENCIES) . '.');
        }
        $this->frequency = $frequency;
        return $this;
    }

    /**
     * Setter - Sets the route showhed priority
     * 
     * @param float $priority
     * 
     * @return self
     */
    public function setPriority(float $priority): self
    {
        if($priority < 0 || $priority > 1) {
            throw new InvalidArgumentException('Priority ' . $priority . ' must be more or equal than 0 and less or equal than 1');
        }
        $this->priority = $priority;
        return $this;
    }
}
