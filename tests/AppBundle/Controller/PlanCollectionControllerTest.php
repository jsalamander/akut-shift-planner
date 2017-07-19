<?php

namespace AppBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class PlanCollectionControllerTest extends WebTestCase
{
    private $crawler;

    private $client;

    public function setUp()
    {
        $fixtures = $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadCompleteDataSet'
        ))->getReferenceRepository();

        $this->loginAs($fixtures->getReference('admin-user'), 'main');
        $this->client = $this->makeClient();
        $this->client->request('GET', '/plancollection');
        $this->assertEquals(301, $this->client->getResponse()->getStatusCode());
        $this->crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testIndex()
    {
        $this->assertContains('Plansammlungen', $this->crawler->filter('.justify-content-end')->text());
        $this->assertContains('Warnung! Keine Plan Sammlung erstellt', $this->crawler->filter('.alert-warning')->text());
    }

    public function createACollection()
    {

    }

}
