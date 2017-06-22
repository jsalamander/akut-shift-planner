<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test Plan Controller from authenticated perspective
 *
 * Class PlanControllerTest
 * @package Tests\AppBundle\Controller
 */
class PlanControllerTestAuthenticated extends WebTestCase
{

    private $crawler;

    private $client;

    public function setUp()
    {
        $fixtures = $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadUserData'
        ))->getReferenceRepository();

        $this->loginAs($fixtures->getReference('admin-user'), 'main');
        $this->client = $this->makeClient();
        $this->client->request('GET', '/plan');
        $this->assertEquals(301, $this->client->getResponse()->getStatusCode());
        $this->crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testIndex()
    {
        $this->assertContains('Schichtplan Liste', $this->crawler->filter('.justify-content-end')->text());
        $this->assertContains('Warnung! Keine kommenden Schichtpläne', $this->crawler->filter('.alert-warning')->text());
    }

    public function testCreatePlan()
    {
        $this->crawler = $this->client->request('GET', '/plan/new');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form = $this->crawler->filter('.btn')->form(array(
            'appbundle_plan[title]' => 'test plan',
            'appbundle_plan[date]' => '2017-06-20',
            'appbundle_plan[description]' => 'some desc',
        ));

        $values = $form->getPhpValues();

        $values['appbundle_plan']['shifts'][0]['title'] = 'foo';
        $values['appbundle_plan']['shifts'][0]['description'] = 'bar';
        $values['appbundle_plan']['shifts'][0]['start'] = '00:00';
        $values['appbundle_plan']['shifts'][0]['end'] = '00:01';
        $values['appbundle_plan']['shifts'][0]['numberPeople'] = 3;

        $crawler = $this->client->request($form->getMethod(), $form->getUri(), $values,
            $form->getPhpFiles());

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->assertEquals(0, $crawler->filter('.alert')->count());

        //test plan_show page
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('test plan', $crawler->filter('.justify-content-end')->text());
        $this->assertContains('20.06.2017', $crawler->filter('.justify-content-end')->text());
        $this->assertContains('some desc', $crawler->filter('blockquote')->text());
        $this->assertContains('foo', $crawler->filter('tr')->eq(1)->text());
        $this->assertContains('bar', $crawler->filter('tr')->eq(1)->text());
        $this->assertContains('00:00', $crawler->filter('tr')->eq(1)->text());
        $this->assertContains('00:01', $crawler->filter('tr')->eq(1)->text());
        $this->assertContains('edit', $crawler->filter('.main-row .col-12')->eq(1)->filter('a')->attr('href'));
        $this->assertEquals(0, $crawler->filter('#passwordPrompt')->count());
        $this->assertEquals(3, $crawler->filter('.container .text-nowrap')->count());

        //go to overview page
        $this->client->request('GET', '/plan');
        $this->crawler = $this->client->followRedirect();
        $this->assertContains('test plan', $this->crawler->text());
    }

    public function testCreatePlanTemplate()
    {
        $this->crawler = $this->client->request('GET', '/plan/new');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form = $this->crawler->filter('.btn')->form(array(
            'appbundle_plan[title]' => 'test plan template',
            'appbundle_plan[date]' => '2017-06-20',
            'appbundle_plan[description]' => 'some desc',
            'appbundle_plan[isTemplate]' => true,
            'appbundle_plan[isPublic]' => true
        ));

        $values = $form->getPhpValues();

        $values['appbundle_plan']['shifts'][0]['title'] = 'foo';
        $values['appbundle_plan']['shifts'][0]['description'] = 'bar';
        $values['appbundle_plan']['shifts'][0]['start'] = '00:00';
        $values['appbundle_plan']['shifts'][0]['end'] = '00:01';
        $values['appbundle_plan']['shifts'][0]['numberPeople'] = 3;

        $this->client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();

        //test plan_show page
        $this->assertContains('test plan template', $crawler->filter('.justify-content-end')->text());
        $this->assertContains('some desc', $crawler->filter('blockquote')->text());
        $this->assertContains('foo', $crawler->filter('tr')->eq(1)->text());
        $this->assertContains('bar', $crawler->filter('tr')->eq(1)->text());
        $this->assertContains('00:00', $crawler->filter('tr')->eq(1)->text());
        $this->assertContains('00:01', $crawler->filter('tr')->eq(1)->text());
        $this->assertContains('edit', $crawler->filter('.main-row .col-12')->eq(1)->filter('a')->attr('href'));
        $this->assertEquals(0, $crawler->filter('#passwordPrompt')->count());
        $this->assertEquals(3, $crawler->filter('td > ol > li')->count());

        //go to overview page
        $this->crawler = $this->client->request('GET', '/plan/templates');
        $this->assertContains('test plan template', $this->crawler->text());
        $this->assertEquals(1, $this->crawler->filter('td > a')->count());
        $this->assertEquals('Bearbeiten', $this->crawler->filter('td > a')->eq(0)->text());
    }

    public function testCreateWithoutShiftPlan()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/plan/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('.btn')->form(array(
            'appbundle_plan[title]' => 'test plan',
            'appbundle_plan[date]' => '2017-06-20',
            'appbundle_plan[description]' => 'some desc'
        ));

        $crawler = $client->submit($form);
        $this->assertEquals(1, $crawler->filter('.alert')->count());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreatePlanErrors()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/plan/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('.btn')->form(array(
            'appbundle_plan[title]' => 't',
            'appbundle_plan[date]' => '340.01.2017',
            'appbundle_plan[description]' => 's'
        ));

        $values = $form->getPhpValues();

        $values['appbundle_plan']['shifts'][0]['description'] = 'w';
        $values['appbundle_plan']['shifts'][0]['title'] = 'w';
        $values['appbundle_plan']['shifts'][0]['start'] = '00:00:sdf';
        $values['appbundle_plan']['shifts'][0]['end'] = '00:0:sdf';
        $values['appbundle_plan']['shifts'][0]['numberPeople'] = 0;

        $crawler = $client->request($form->getMethod(), $form->getUri(), $values,
            $form->getPhpFiles());

        $this->assertEquals(8, $crawler->filter('.alert')->count());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
