<?php

namespace Tests\App\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test Shifts
 *
 * Class ShiftControllerYourShiftTest
 * @package Tests\App\Controller
 */
class ShiftControllerYourShiftTest extends WebTestCase
{
    private $client;

    private $fixtures;

    public function setUp() {
        $this->fixtures = $this->loadFixtures(array(
            'App\DataFixtures\ORM\LoadCompleteDataSet'
        ))->getReferenceRepository();

        $this->loginAs($this->fixtures->getReference('admin-user'), 'main');
        $this->client = $this->makeClient();
    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET',
            '/person/new?shift=' . $this->fixtures->getReference('admin-shift')->getId());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->filter('.btn')->form(array(
            'App_person[name]' => 'im admin',
            'App_person[alias]' => 'my alias',
            'App_person[email]' => 'email@mail.com',
        ));

        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->followRedirect();

        $crawler = $this->client->request('GET',
            '/person/new?shift=' . $this->fixtures->getReference('admin-shift-past')->getId());
        $form = $crawler->filter('.btn')->form(array(
            'App_person[name]' => 'im admin',
            'App_person[alias]' => 'my alias',
            'App_person[email]' => 'email@mail.com',
        ));

        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->followRedirect();

        $this->client->request('GET', '/shift');
        $crawler = $this->client->followRedirect();
        $this->assertSelectorTextContains('tbody', 'admin shift');
        $this->assertNotContains('admin shift past', $crawler->filter('tbody')->text());
        $this->assertContains(
            '/plan/' . $this->fixtures->getReference('admin-plan')->getId(),
            $crawler->filter('tbody a')->attr('href')
        );
    }

}
