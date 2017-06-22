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

    public function setUp() {
        $fixtures = $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadCompleteDataSet'
        ))->getReferenceRepository();
        $planRef = $fixtures->getReference('admin-plan');
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
            'appbundle_person[phone]' => '079343134343'
        ));

        $this->client->submit($form);
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        $this->assertContains('alias', $crawler->filter('ol > li')->eq(0)->text());
        $this->assertNotContains('private name', $crawler->filter('ol > li')->eq(0)->text());
    }

    public function testErrorEnrollment()
    {
        $link = $this->crawler->filter('ol > li > a')->eq(0)->link();
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
}
