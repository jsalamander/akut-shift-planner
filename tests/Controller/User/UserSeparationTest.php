<?php

namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class UserSeparationTest extends WebTestCase
{

    use FixturesTrait;

    private $fixtures;

    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(array(
            'App\DataFixtures\LoadCompleteDataSet'
        ))->getReferenceRepository();
    }

    public function testPlansAppearOnlyOnOwnProfileAdmin() {
        $this->loginAs($this->fixtures->getReference('admin-user'), 'main');
        $client = $this->makeClient();
        $client->request('GET', '/plan');
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertSelectorTextContains('tbody', 'admin plan');
        $this->assertNotContains('rudolf plan', $crawler->filter('tbody')->text());
    }

    public function testPlansAppearOnlyOnOwnProfileRudolf() {
        $this->loginAs($this->fixtures->getReference('rudolf-user'), 'main');
        $client = $this->makeClient();
        $client->request('GET', '/plan');
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertNotContains('admin plan', $crawler->filter('tbody')->text());
        $this->assertSelectorTextContains('tbody', 'rudolf plan');
    }

    public function testTemplateAppearOnlyOnOwnProfileAdmin() {
        $this->loginAs($this->fixtures->getReference('admin-user'), 'main');
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/plan/templates');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertSelectorTextContains('tbody', 'admin plan template');
        $this->assertNotContains('rudolf plan template', $crawler->filter('tbody')->text());
    }

    public function testTemplateAppearOnlyOnOwnProfileRudolf() {
        $this->loginAs($this->fixtures->getReference('rudolf-user'), 'main');
        $client = $this->makeClient();
        $crawler =  $client->request('GET', '/plan/templates');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertNotContains('admin plan template', $crawler->filter('tbody')->text());
        $this->assertSelectorTextContains('tbody', 'rudolf plan template');
    }
}
