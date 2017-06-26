<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class UserGroupTest extends WebTestCase
{

    private $fixtures;

    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadCompleteDataSet'
        ))->getReferenceRepository();
    }

    public function testCreateGroup() {
        $this->loginAs($this->fixtures->getReference('admin-user'), 'main');
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/group/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertContains('Gruppe erstellen', $crawler->filter('h1')->text());
        $form = $crawler->filter('.btn')->form(
            array(
                'fos_user_group_form[name]' => 'new group'
            )
        );

        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertContains('new group', $crawler->filter('.main-row')->text());

    }

    public function testCreateGroupWithError() {
        $this->loginAs($this->fixtures->getReference('admin-user'), 'main');
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/group/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertContains('Gruppe erstellen', $crawler->filter('h1')->text());
        $form = $crawler->filter('.btn')->form(
            array(
                'fos_user_group_form[name]' => 'a'
            )
        );

        $crawler = $client->submit($form);
        $this->assertEquals(1, $crawler->filter('.alert')->count());
    }
}
