<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test Enrollment with auth
 *
 * @package Tests\AppBundle\Controller
 */
class PlanControllerEnrollmentAuthenticatedTest extends WebTestCase
{
    private $crawler;

    private $client;

    private $fixtures;

    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadPlanWithPeople'
        ))->getReferenceRepository();
        $this->loginAs($this->fixtures->getReference('admin-user'), 'main');
        $planRef = $this->fixtures->getReference('admin-plan');
        $this->client = $this->makeClient();
        $this->crawler = $this->client->request('GET', '/plan/' . $planRef->getId());
    }

    public function testSimpleEnrollment()
    {
        $this->assertContains('/person/1/edit', $this->crawler->filter('.edit')->attr('href'));
        $this->assertContains('private name', $this->crawler->filter('ol > li')->eq(0)->text());
        $this->assertContains('mailto:asdf@asfd.de?Subject=Kontakt Schichtplan: admin plan',
            $this->crawler->filter('#person-details li > a')->eq(0)->attr('href'));
        $this->assertContains('09797873', $this->crawler->filter('#person-details li')->eq(1)->text());

        $this->assertContains('09797873', $this->crawler->filter('#person-details:nth-child(2) li')->eq(1)->text());
        $this->assertContains('mailto:asdf@asfd.de?Subject=Kontakt Schichtplan: admin plan',
            $this->crawler->filter('#person-details li > a')->eq(1)->attr('href'));
        $this->assertContains('/person/2/edit', $this->crawler->filter('.edit')->eq(1)->attr('href'));
        $this->assertContains('private name', $this->crawler->filter('ol > li')->eq(1)->text());
    }

    public function testEditErrorEnrollment()
    {
        $link = $this->crawler->filter('.edit')->eq(0)->link();
        $crawler = $this->client->click($link);
        $form = $crawler->filter('.btn')->form(array(
            'appbundle_person[name]' => 'p',
            'appbundle_person[alias]' => 'a',
            'appbundle_person[email]' => 'testenroll.ch',
            'appbundle_person[phone]' => '079343134343'
        ));

        $crawler = $this->client->submit($form);
        $this->assertEquals(3, $crawler->filter('.alert')->count());
    }

    public function testEditEnrollment()
    {
        $link = $this->crawler->filter('.edit')->eq(0)->link();
        $this->crawler = $this->client->click($link);
        $form = $this->crawler->filter('.btn')->form(array(
            'appbundle_person[name]' => 'new name',
            'appbundle_person[alias]' => 'new alias',
            'appbundle_person[email]' => 'new@email.ch',
            'appbundle_person[phone]' => '000000'
        ));

        $this->client->submit($form);
        $this->crawler = $this->client->followRedirect();
        $this->assertContains('new name', $this->crawler->filter('ol')->eq(0)->text());
        $this->assertContains('new@email.ch', $this->crawler->filter('ol')->eq(0)->text());
        $this->assertContains('000000', $this->crawler->filter('ol')->eq(0)->text());
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
            $this->crawler->filter('td > ol > li')->eq(1)->filter('a')->attr('href'));
    }
}
