<?php

namespace AppBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class PlanCollectionControllerTest extends WebTestCase
{
    private $crawler;

    private $client;

    private $fixtures;

    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadCompleteDataSet'
        ))->getReferenceRepository();

        $this->loginAs($this->fixtures->getReference('admin-user'), 'main');
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

    public function testCreateACollection()
    {
        $link = $this->crawler->filter('.btn')->link();
        $this->crawler = $this->client->click($link);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Neue Plansammlung', $this->crawler->filter('h1')->text());
        $this->createACollection();
        $this->assertContains('admin plan', $this->crawler->filter('tbody')->text());
        $this->assertContains(
            'http://localhost/plan/'.$this->fixtures->getReference('admin-plan')->getId(),
            $this->crawler->filter('tbody')->text()
        );
    }

    public function testNoTitle () {
        $this->crawler = $this->client->request('GET', '/plancollection/new');
        $adminPlanId = $this->fixtures->getReference('admin-plan')->getId();
        $form = $this->crawler->filter('.btn-primary')->form(array(
            'appbundle_plancollection[title]' => '',
            'appbundle_plancollection[plans]' => array($adminPlanId)
        ));

        $this->crawler = $this->client->submit($form);
        $this->assertEquals(1, $this->crawler->filter('.alert')->count());
    }

    public function createACollection()
    {
        $adminPlanId = $this->fixtures->getReference('admin-plan')->getId();
        $form = $this->crawler->filter('.btn-primary')->form(array(
            'appbundle_plancollection[title]' => 'test collection',
            'appbundle_plancollection[plans]' => array($adminPlanId)
        ));

        $this->crawler = $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->crawler = $this->client->followRedirect();
    }
}
