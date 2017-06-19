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
}
