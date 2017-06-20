<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlanControllerTest extends WebTestCase
{
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

    public function testCreatePlan()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/plan/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('.btn')->form(array(
            'appbundle_plan[title]' => 'test plan',
            'appbundle_plan[date]' => '01.01.2017',
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

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateWithoutShiftPlan()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/plan/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('.btn')->form(array(
            'appbundle_plan[title]' => 'test plan',
            'appbundle_plan[date]' => '01.01.2017',
            'appbundle_plan[description]' => 'some desc',
            'appbundle_plan[email]' => 'test@test.ch',
            'appbundle_plan[password][first]' => '12345678',
            'appbundle_plan[password][second]' => '12345678',
        ));

        $crawler = $client->submit($form);
        $this->assertEquals(1, $crawler->filter('.alert')->count());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
