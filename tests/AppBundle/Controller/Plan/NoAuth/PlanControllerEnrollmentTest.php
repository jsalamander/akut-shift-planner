<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test Enrollment
 *
 * @package Tests\AppBundle\Controller
 */
class PlanControllerEnrollmentTest extends WebTestCase
{
    private $crawler;

    private $client;

    private $fixtures;

    public function setUp() {
        $this->fixtures = $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadCompleteDataSet'
        ))->getReferenceRepository();
        $planRef = $this->fixtures->getReference('admin-plan');
        $this->client = $this->makeClient();
        $this->crawler = $this->client->request('GET', '/plan/' . $planRef->getId());
    }

    public function testSimpleEnrollment()
    {
        $link = $this->crawler->filter('ol > li > a')->eq(0)->link();
        $crawler = $this->client->click($link);
        $form = $crawler->filter('.btn')->form(array(
            'appbundle_person[name]' => 'private name',
            'appbundle_person[alias]' => 'alias',
            'appbundle_person[email]' => 'test@enroll.ch',
            'appbundle_person[phone]' => '0795435432'
        ));

        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        $this->assertContains('alias', $crawler->filter('ol > li')->eq(0)->text());
        $this->assertNotContains('private name', $crawler->filter('ol > li')->eq(0)->text());
    }

    /**
     * TODO: validate phone number
     */
    public function testErrorEnrollment()
    {
        $link = $this->crawler->filter('ol > li > a')->eq(0)->link();
        $crawler = $this->client->click($link);
        $form = $crawler->filter('.btn')->form(array(
            'appbundle_person[name]' => 'p',
            'appbundle_person[alias]' => 'a',
            'appbundle_person[email]' => 'testenroll.ch',
            'appbundle_person[phone]' => '07934123123123123123'
        ));

        $crawler = $this->client->submit($form);
        $this->assertEquals(2, $crawler->filter('.alert')->count());
    }

    public function testFullEnrollment()
    {
        $this->enrollSamplePerson();
        $this->enrollSamplePerson();
        $this->assertContains('alias', $this->crawler->filter('ol > li')->eq(0)->text());
        $this->assertContains('alias', $this->crawler->filter('ol > li')->eq(1)->text());
        $this->assertEquals(0, $this->crawler->filter('ol > li > a')->count());
    }

    private function enrollSamplePerson() {
        $link = $this->crawler->filter('ol > li > a')->eq(0)->link();
        $this->crawler = $this->client->click($link);
        $form = $this->crawler->filter('.btn')->form(array(
            'appbundle_person[name]' => 'private name',
            'appbundle_person[alias]' => 'alias',
            'appbundle_person[email]' => 'test@enroll.ch',
            'appbundle_person[phone]' => '0793435645'
        ));

        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->crawler = $this->client->followRedirect();
        $this->assertContains('alias', $this->crawler->filter('ol > li')->eq(0)->text());
        $this->assertNotContains('private name', $this->crawler->filter('ol > li')->eq(0)->text());
    }

    public function testTooManyEnrollments()
    {
        $this->enrollSamplePerson();
        $this->enrollSamplePerson();
        $this->crawler = $this->client->request('GET', '/person/new?shift=' . $this->fixtures->getReference('admin-shift')->getId());
        $form = $this->crawler->filter('.btn')->form(array(
            'appbundle_person[name]' => 'private name',
            'appbundle_person[alias]' => 'alias',
            'appbundle_person[email]' => 'test@enroll.ch',
            'appbundle_person[phone]' => '0794564534'
        ));

        $this->crawler = $this->client->submit($form);
        $this->assertContains('The shift is full', $this->crawler->filter('.alert')->text());
    }
}
