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
        $this->crawler = $this->client->request('GET', '/logout');
        $this->crawler = $this->client->request('GET', '/plancollection/testcollection');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->crawler = $this->client->request('GET', '/plancollection/');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->crawler = $this->client->request('GET', '/plancollection/testcollection/edit');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
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

    public function testInvalidTitle () {
        $this->crawler = $this->client->request('GET', '/plancollection/new');
        $adminPlanId = $this->fixtures->getReference('admin-plan')->getId();
        $form = $this->crawler->filter('.btn-primary')->form(array(
            'appbundle_plancollection[title]' => 'asdf asdf&%/&(/)',
            'appbundle_plancollection[plans]' => array($adminPlanId)
        ));

        $this->crawler = $this->client->submit($form);
        $this->assertContains("Es sind nur Buchstaben, Zahlen und Bindestriche erlaubt", $this->crawler->filter('.alert')->text());
    }

    public function testEditPlanCollection()
    {
        $this->crawler = $this->client->request('GET', '/plancollection/admincollection/edit');
        $form = $this->crawler->filter('.btn-primary')->form(array(
            'appbundle_plancollection[title]' => 'testcollection',
            'appbundle_plancollection[plans]' => array(
                $this->fixtures->getReference('admin-plan-second')->getId()
            )
        ));

        $this->crawler = $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->crawler = $this->client->followRedirect();

        $this->assertContains('admin plan', $this->crawler->filter('tbody')->text());
        $this->assertContains(
            'http://localhost/plan/'.$this->fixtures->getReference('admin-plan-second')->getId(),
            $this->crawler->filter('tbody')->text()
        );
    }

    public function testShowCollection()
    {
        $this->crawler = $this->client->request('GET', '/plancollection/admincollection');
        $this->assertContains('admincollection', $this->crawler->filter('h1')->text());
        $this->assertContains('Schichtpläne', $this->crawler->filter('thead')->text());
        $this->assertContains('Link', $this->crawler->filter('thead')->text());
        $this->assertContains('admin plan', $this->crawler->filter('td')->eq(0)->text());
        $this->assertContains(
            'http://localhost/plan/'.$this->fixtures->getReference('admin-plan')->getId(),
            $this->crawler->filter('td')->eq(1)->text()
        );
    }

    public function createACollection()
    {
        $adminPlanId = $this->fixtures->getReference('admin-plan')->getId();
        $form = $this->crawler->filter('.btn-primary')->form(array(
            'appbundle_plancollection[title]' => 'testcollection',
            'appbundle_plancollection[plans]' => array($adminPlanId)
        ));

        $this->crawler = $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->crawler = $this->client->followRedirect();
    }
}
