<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class UserSeparationTest extends WebTestCase
{

    private $fixtures;

    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadCompleteDataSet'
        ))->getReferenceRepository();
    }

    public function testPlansAppearOnlyOnOwnProfileAdmin() {
        $this->loginAs($this->fixtures->getReference('admin-user'), 'main');
        $client = $this->makeClient();
        $client->request('GET', '/plan');
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertContains('admin plan', $crawler->filter('tbody')->text());
        $this->assertNotContains('rudolf plan', $crawler->filter('tbody')->text());
    }

    public function testPlansAppearOnlyOnOwnProfileRudolf() {
        $this->loginAs($this->fixtures->getReference('rudolf-user'), 'main');
        $client = $this->makeClient();
        $client->request('GET', '/plan');
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertNotContains('admin plan', $crawler->filter('tbody')->text());
        $this->assertContains('rudolf plan', $crawler->filter('tbody')->text());
    }

    public function testTemplateAppearOnlyOnOwnProfileAdmin() {
        $this->loginAs($this->fixtures->getReference('admin-user'), 'main');
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/plan/templates');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertContains('admin plan template', $crawler->filter('tbody')->text());
        $this->assertNotContains('rudolf plan template', $crawler->filter('tbody')->text());
    }

    public function testTemplateAppearOnlyOnOwnProfileRudolf() {
        $this->loginAs($this->fixtures->getReference('rudolf-user'), 'main');
        $client = $this->makeClient();
        $crawler =  $client->request('GET', '/plan/templates');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertNotContains('admin plan template', $crawler->filter('tbody')->text());
        $this->assertContains('rudolf plan template', $crawler->filter('tbody')->text());
    }
}
