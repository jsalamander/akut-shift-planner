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
        $this->assertContains('01.01.2099', $this->crawler->filter('tbody')->text());
        $this->assertContains(
            $this->fixtures->getReference('admin-plan')->getId(),
            $this->crawler->filter('tr')->eq(1)->filter('a')->attr('href')
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
        $this->assertContains('admin second plan' ,$this->crawler->filter('option')->eq(0)->text());
        $this->assertContains('admin plan' ,$this->crawler->filter('option')->eq(1)->text());
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
            $this->fixtures->getReference('admin-plan-second')->getId(),
            $this->crawler->filter('tr')->eq(1)->filter('a')->attr('href')
        );
    }

    public function testShowCollection()
    {
        $this->crawler = $this->client->request('GET', '/plancollection/admincollection');
        $this->assertContains('admincollection', $this->crawler->filter('h1')->text());
        $this->assertContains('Schichtpläne', $this->crawler->filter('thead')->text());
        $this->assertContains('admin plan', $this->crawler->filter('tr')->eq(2)->text());
        $this->assertContains('/plan/' . $this->fixtures->getReference('admin-plan')->getId(),
            $this->crawler->filter('tr')->eq(2)->filter('a')->attr('href'));
        $this->assertContains('admin second plan', $this->crawler->filter('tr')->eq(1)->text());
        $this->assertContains(' 0 von 0', $this->crawler->filter('tbody tr td')->eq(2)->text());
        $this->assertContains(' 1 von 3', $this->crawler->filter('tbody tr')->eq(1)->filter('tr td')->eq(2)->text());
    }

    public function createACollection()
    {
        $adminPlanId = $this->fixtures->getReference('admin-plan')->getId();
        $form = $this->crawler->filter('.btn-primary')->form(array(
            'appbundle_plancollection[title]' => 'testcollection',
            'appbundle_plancollection[plans]' => array($adminPlanId)
        ));

        // make sure the order is correct and past plans aren't available
        $this->assertNotContains('admin plan past' ,$this->crawler->filter('option')->eq(0)->text());
        $this->assertContains('admin second plan' ,$this->crawler->filter('option')->eq(0)->text());
        $this->assertContains('admin plan' ,$this->crawler->filter('option')->eq(1)->text());

        // var_dump($this->crawler->filter('option'));die;


        $this->assertNotEquals('admin plan template', $this->crawler->filter('#appbundle_plancollection_plans')->text());
        $this->crawler = $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->crawler = $this->client->followRedirect();
    }
}
