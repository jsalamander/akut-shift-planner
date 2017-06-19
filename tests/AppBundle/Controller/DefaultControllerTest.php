<?php

namespace Tests\AppBundle\Controller;

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
        $this->assertEquals(4, $crawler->filter('.navbar-collapse li')->count());

    }

    public function testFooter() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(2, $crawler->filter('.footer span')->count());
        $this->assertContains('mailto:jan.friedli@gmx.ch', $crawler->filter('.footer span a')->attr('href'));
        $this->assertContains('https://github.com/fribim/akut-shift-planner', $crawler->filter('.footer span a')->eq(1)->attr('href'));
    }
}
