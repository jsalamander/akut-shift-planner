<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test Plan Controller from no auth perspective
 *
 * Class PlanControllerTest
 * @package Tests\AppBundle\Controller
 */
class PlanControllerTest extends WebTestCase
{

    //use an empty db
    public function setUp() {
        $this->loadFixtures(array());
    }

    public function testIndexWithoutAuth()
    {
        $client = static::createClient();
        $client->request('GET', '/plan');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue(
            $client->getResponse()->isRedirect('http://localhost/login')
        );

        $client->request('GET', '/plan/templates');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue(
            $client->getResponse()->isRedirect('http://localhost/login')
        );
    }

    public function testCreatePlanWithoutAuth()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/plan/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('.btn')->form(array(
            'appbundle_plan[title]' => 'test plan',
            'appbundle_plan[date]' => '2017-06-20',
            'appbundle_plan[description]' => 'some desc',
            'appbundle_plan[email]' => 'test@test.ch',
            'appbundle_plan[password][first]' => '12345678',
            'appbundle_plan[password][second]' => '12345678',
        ));

        $values = $form->getPhpValues();

        $values['appbundle_plan']['shifts'][0]['title'] = 'foo';
        $values['appbundle_plan']['shifts'][0]['description'] = 'bar';
        $values['appbundle_plan']['shifts'][0]['start'] = '00:00';
        $values['appbundle_plan']['shifts'][0]['end'] = '00:01';
        $values['appbundle_plan']['shifts'][0]['numberPeople'] = 3;

        $crawler = $client->request($form->getMethod(), $form->getUri(), $values,
            $form->getPhpFiles());

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
        $this->assertEquals(0, $crawler->filter('.alert')->count());

        //test plan_show page
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('test plan', $crawler->filter('.justify-content-end')->text());
        $this->assertContains('20.06.2017', $crawler->filter('.justify-content-end')->text());
        $this->assertContains('some desc', $crawler->filter('blockquote')->text());
        $this->assertContains('foo', $crawler->filter('tr')->eq(1)->text());
        $this->assertContains('bar', $crawler->filter('tr')->eq(1)->text());
        $this->assertContains('00:00', $crawler->filter('tr')->eq(1)->text());
        $this->assertContains('00:01', $crawler->filter('tr')->eq(1)->text());
        $this->assertContains('#', $crawler->filter('#passwordPrompt')->attr('href'));
        $this->assertEquals(3, $crawler->filter('.container .text-nowrap')->count());
        $this->assertEquals(1, $crawler->filter('.modal-content')->count());
        $this->assertContains('/login_check', $crawler->filter('.modal-content form')->attr('action'));
    }

    public function testCreateWithoutShiftPlanWithoutAuth()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/plan/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('.btn')->form(array(
            'appbundle_plan[title]' => 'test plan',
            'appbundle_plan[date]' => '2017-06-20',
            'appbundle_plan[description]' => 'some desc',
            'appbundle_plan[email]' => 'test1@test.ch',
            'appbundle_plan[password][first]' => '12345678',
            'appbundle_plan[password][second]' => '12345678',
        ));

        $crawler = $client->submit($form);
        $this->assertEquals(1, $crawler->filter('.alert')->count());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreatePlanErrorsWithoutAuth()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/plan/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('.btn')->form(array(
            'appbundle_plan[title]' => 't',
            'appbundle_plan[date]' => '340.01.2017',
            'appbundle_plan[description]' => 's',
            'appbundle_plan[email]' => 'test.test.ch',
            'appbundle_plan[password][first]' => '1234567',
            'appbundle_plan[password][second]' => '12345678',
        ));

        $values = $form->getPhpValues();

        $values['appbundle_plan']['shifts'][0]['description'] = 'w';
        $values['appbundle_plan']['shifts'][0]['title'] = 'w';
        $values['appbundle_plan']['shifts'][0]['start'] = '00:00:sdf';
        $values['appbundle_plan']['shifts'][0]['end'] = '00:0:sdf';
        $values['appbundle_plan']['shifts'][0]['numberPeople'] = 0;

        $crawler = $client->request($form->getMethod(), $form->getUri(), $values,
            $form->getPhpFiles());

        $this->assertEquals(10, $crawler->filter('.alert')->count());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
