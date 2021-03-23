<?php

namespace Tests\App\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test Enrollment with auth
 *
 * @package Tests\App\Controller
 */
class PlanControllerEnrollmentAuthenticatedTest extends WebTestCase
{
    private $crawler;

    private $client;

    private $fixtures;

    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(array(
            'App\DataFixtures\ORM\LoadPlanWithPeople'
        ))->getReferenceRepository();
        $this->loginAs($this->fixtures->getReference('admin-user'), 'main');
        $planRef = $this->fixtures->getReference('admin-plan');
        $this->client = $this->makeClient();
        $this->crawler = $this->client->request('GET', '/plan/' . $planRef->getId());
    }

    public function testSimpleEnrollment()
    {
        $this->assertContains('/person/1/edit', $this->crawler->filter('.edit')->attr('href'));
        $this->assertContains('private name', $this->crawler->filter('.card')->eq(0)->text());
        $this->assertContains('mailto:asdf@asfd.de?Subject=Kontakt Schichtplan: admin plan',
            $this->crawler->filter('.mailto')->eq(0)->attr('href'));
        $this->assertContains('+41 97 978 73 23', $this->crawler->filter('.phone-number')->eq(1)->text());
        $this->assertContains('mailto:asdf@asfd.de?Subject=Kontakt Schichtplan: admin plan',
            $this->crawler->filter('.mailto')->eq(1)->attr('href'));
        $this->assertContains('/person/2/edit', $this->crawler->filter('.edit')->eq(1)->attr('href'));
        $this->assertContains('private name', $this->crawler->filter('.list-group-item')->eq(1)->text());
    }

    public function testEditErrorEnrollment()
    {
        $link = $this->crawler->filter('.edit')->eq(0)->link();
        $crawler = $this->client->click($link);
        $form = $crawler->filter('.btn')->form(array(
            'App_person[name]' => 'p',
            'App_person[alias]' => 'a',
            'App_person[email]' => 'testenroll.ch',
            'App_person[phone]' => '07934313433333'
        ));

        $crawler = $this->client->submit($form);
        $this->assertEquals(2, $crawler->filter('.alert')->count());
    }

    public function testEditEnrollment()
    {
        $link = $this->crawler->filter('.edit')->eq(0)->link();
        $this->crawler = $this->client->click($link);
        $form = $this->crawler->filter('.btn')->form(array(
            'App_person[name]' => 'new name',
            'App_person[alias]' => 'new alias',
            'App_person[email]' => 'new@email.ch',
            'App_person[phone]' => '0795643243'
        ));

        $this->client->submit($form);
        $this->crawler = $this->client->followRedirect();
        $this->assertContains('new name', $this->crawler->filter('.list-group-item')->eq(0)->text());
        $this->assertContains('new@email.ch', $this->crawler->filter('.list-group-item')->eq(0)->text());
        $this->assertContains('+41 79 564 32 43', $this->crawler->filter('.list-group-item')->eq(0)->text());
    }

    public function testDeletePerson()
    {
        $link = $this->crawler->filter('.edit')->eq(0)->link();
        $this->crawler = $this->client->click($link);
        $form = $this->crawler->filter('.btn-danger')->form();
        $this->client->submit($form);
        $this->crawler = $this->client->followRedirect();
        $this->assertContains('/person/2/edit', $this->crawler->filter('.edit')->attr('href'));
        $this->assertContains('/person/new?shift=' . $this->fixtures->getReference('admin-shift')->getId(),
            $this->crawler->filter('.btn-primary')->eq(0)->attr('href'));
    }
}
