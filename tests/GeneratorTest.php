<?php

declare(strict_types=1);

namespace Tests;

use DateTime;
use InvalidArgumentException;
use Nolikein\SitemapGenerator\SitemapFactory;
use Nolikein\SitemapGenerator\SitemapRoute;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
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

    public function test_normal_case()
    {
        $generator = new SitemapFactory();
        $generator->addRoute('http://domain.org/test1')
            ->addRoute('http://domain.org//test2', new DateTime('now'))
            ->addRoute('http://domain.org//test3', new DateTime('now'), 'always')
            ->addRoute('http://domain.org/test4', new DateTime('now'), 'always', 1);

        /** @var bool $hasTest1 */
        $hasTest1 = false;
        foreach ($generator->getRoutes() as $route) {
            /** @var SitemapRoute $route */
            switch ($route->getUrl()) {
                case 'http://domain.org/test1':
                    $hasTest1 = true;
                    break;
                case 'http://domain.org/test2':
                    $this->assertInstanceOf(Datetime::class, $route->getLastmodification());
                    break;
                case 'http://domain.org/test3':
                    $this->assertInstanceOf(Datetime::class, $route->getLastmodification());
                    $this->assertEquals('always', $route->getFrequency());
                    break;
                case 'http://domain.org/test4':
                    $this->assertInstanceOf(Datetime::class, $route->getLastmodification());
                    $this->assertEquals('always', $route->getFrequency());
                    $this->assertEquals(1, $route->getPriority());
                    break;
            }
        }
        $this->assertTrue($hasTest1);
    }

    public function test_with_wrong_url_format()
    {
        $url = 'bad_url';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($url . ' must be an url');
        $generator = new SitemapFactory();
        $generator->addRoute($url);
    }

    public function test_with_wrong_last_modification_date()
    {
        $url = 'http://domain.org/';
        $lastModification = (new Datetime('now'))->modify('+1 day');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The lastmodification property must have a datetime equal or less than now');
        $generator = new SitemapFactory();
        $generator->addRoute($url, $lastModification);
    }

    public function test_with_wrong_frequency()
    {
        $url = 'http://domain.org/';
        $lastModification = new Datetime('now');
        $frequency = 'does not exists';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Frequency ' . $frequency . ' is invalid. Choose one of the following: ' . implode(', ', self::ACCEPTED_FREQUENCIES) . '.');
        $generator = new SitemapFactory();
        $generator->addRoute($url, $lastModification, $frequency);
    }

    public function test_with_wrong_priority_less_than_well_format()
    {
        $url = 'http://domain.org/';
        $lastModification = new Datetime('now');
        $frequency = 'never';
        $priority = -0.1;
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Priority ' . $priority . ' must be more or equal than 0 and less or equal than 1');
        $generator = new SitemapFactory();
        $generator->addRoute($url, $lastModification, $frequency, $priority);
    }

    public function test_with_wrong_priority_more_than_well_format()
    {
        $url = 'http://domain.org/';
        $lastModification = new Datetime('now');
        $frequency = 'never';
        $priority = 1.1;
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Priority ' . $priority . ' must be more or equal than 0 and less or equal than 1');
        $generator = new SitemapFactory();
        $generator->addRoute($url, $lastModification, $frequency, $priority);
    }
}
