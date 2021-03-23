<?php

namespace Tests\App\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test Plan Controller from no auth perspective
 *
 * Class PlanControllerTest
 * @package Tests\App\Controller
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

        $date = new \DateTime();
        $form = $crawler->filter('.btn')->form(array(
            'App_plan[title]' => 'test plan',
            'App_plan[date]' => $date->format('Y-m-d'),
            'App_plan[description]' => 'some desc',
            'App_plan[email]' => 'test@test.ch',
            'App_plan[password][first]' => '12345678',
            'App_plan[password][second]' => '12345678',
        ));

        $values = $form->getPhpValues();

        $values['App_plan']['shifts'][0]['title'] = 'foo';
        $values['App_plan']['shifts'][0]['description'] = 'bar';
        $values['App_plan']['shifts'][0]['start'] = '00:00';
        $values['App_plan']['shifts'][0]['end'] = '00:01';
        $values['App_plan']['shifts'][0]['numberPeople'] = 3;

        $crawler = $client->request($form->getMethod(), $form->getUri(), $values,
            $form->getPhpFiles());

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
        $this->assertEquals(0, $crawler->filter('.alert')->count());

        //test plan_show page
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('test plan', $crawler->filter('.justify-content-end')->text());
        $this->assertContains($date->format('d.m.Y'), $crawler->filter('.justify-content-end')->text());
        $this->assertContains('some desc', $crawler->filter('blockquote')->text());
        $this->assertContains('foo', $crawler->filter('.card')->eq(0)->text());
        $this->assertContains('bar', $crawler->filter('.card')->eq(0)->text());
        $this->assertContains('00:00', $crawler->filter('.card')->eq(0)->text());
        $this->assertContains('00:01', $crawler->filter('.card')->eq(0)->text());
        $this->assertContains('#', $crawler->filter('#passwordPrompt')->attr('href'));
        $this->assertContains('3', $crawler->filter('.progress')->text());
        $this->assertEquals(1, $crawler->filter('.modal-content')->count());
        $this->assertContains('/login_check', $crawler->filter('.modal-content form')->attr('action'));
    }

    public function testCreateWithoutShiftPlanWithoutAuth()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/plan/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('.btn')->form(array(
            'App_plan[title]' => 'test plan',
            'App_plan[date]' => '2099-06-20',
            'App_plan[description]' => 'some desc',
            'App_plan[email]' => 'test1@test.ch',
            'App_plan[password][first]' => '12345678',
            'App_plan[password][second]' => '12345678',
        ));

        $crawler = $client->submit($form);
        $this->assertEquals(5, $crawler->filter('.alert')->count());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreatePlanErrorsWithoutAuth()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/plan/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('.btn')->form(array(
            'App_plan[title]' => 't',
            'App_plan[date]' => '10.01.2017',
            'App_plan[description]' => 's',
            'App_plan[email]' => 'test.test.ch',
            'App_plan[password][first]' => '1234567',
            'App_plan[password][second]' => '12345678',
        ));

        $values = $form->getPhpValues();

        $values['App_plan']['shifts'][0]['description'] = '';
        $values['App_plan']['shifts'][0]['title'] = '';
        $values['App_plan']['shifts'][0]['start'] = '00:00:sdf';
        $values['App_plan']['shifts'][0]['end'] = '00:0:sdf';
        $values['App_plan']['shifts'][0]['numberPeople'] = 0;

        $crawler = $client->request($form->getMethod(), $form->getUri(), $values,
            $form->getPhpFiles());

        $this->assertEquals(10, $crawler->filter('.alert')->count());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
