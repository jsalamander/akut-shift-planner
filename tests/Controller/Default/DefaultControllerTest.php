<?php

namespace Tests\App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Schicht-Plan.ch', $crawler->filter('.jumbotron')->text());
        $this->assertEquals(1, $crawler->filter('.jumbotron .btn')->count());
        $this->assertContains('/plan/new', $crawler->filter('.jumbotron .btn')->attr('href'));
    }

    public function testNavbar() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(5, $crawler->filter('.navbar-collapse li')->count());

    }

    public function testFooter() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(2, $crawler->filter('.footer span')->count());
        $this->assertContains('mailto:jan.friedli@gmx.ch', $crawler->filter('.footer span a')->attr('href'));
        $this->assertContains('https://github.com/janfriedli/akut-shift-planner', $crawler->filter('.footer span a')->eq(1)->attr('href'));
    }

    public function testAbout() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/about');
        $this->assertEquals(3, $crawler->filter('h3')->count());
        $this->assertContains('Schicht-Plan.ch', $crawler->filter('h1')->text());
    }

    public function testChangelog() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/changelog');
        $this->assertEquals(1, $crawler->filter('h1')->count());
        $this->assertContains('Releases', $crawler->filter('h1')->text());
        $this->assertGreaterThan(1, $crawler->filter('.col-12')->count());
    }
}
