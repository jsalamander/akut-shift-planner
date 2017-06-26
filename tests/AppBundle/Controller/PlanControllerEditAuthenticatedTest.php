<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class PlanControllerEditAuthenticatedTest extends WebTestCase
{

    private $crawler;

    private $client;

    private $fixtures;

    public function setUp()
    {
        $this->fixtures = $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadCompleteDataSet'
        ))->getReferenceRepository();

        $this->loginAs($this->fixtures->getReference('admin-user'), 'main');
        $this->client = $this->makeClient();
        $this->crawler = $this->client->request('GET', '/plan/' . $this->fixtures->getReference('admin-plan')->getId());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testEditPlan()
    {
        $link = $this->crawler->filter('.col-12 .pull-right')->link();
        $this->crawler = $this->client->click($link);

        $form = $this->crawler->filter('.btn')->form(array(
            'appbundle_plan[title]' => 'edited title',
            'appbundle_plan[date]' => '2019-06-20',
            'appbundle_plan[description]' => 'new desc',
        ));

        $values = $form->getPhpValues();

        $values['appbundle_plan']['shifts'][0]['title'] = 'new foo';

        $values['appbundle_plan']['shifts'][1]['title'] = 'new shift';
        $values['appbundle_plan']['shifts'][1]['description'] = 'new new';
        $values['appbundle_plan']['shifts'][1]['start'] = '00:05';
        $values['appbundle_plan']['shifts'][1]['end'] = '00:10';
        $values['appbundle_plan']['shifts'][1]['numberPeople'] = 1;

        $this->client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());
        $this->crawler = $this->client->followRedirect();

        $this->assertContains('edited title', $this->crawler->filter('h1')->text());
        $this->assertContains('0.06.2019', $this->crawler->filter('h1')->text());
        $this->assertContains('new desc', $this->crawler->filter('blockquote')->text());
        $this->assertContains('new foo', $this->crawler->filter('tbody > tr')->text());
        $this->assertContains('new shift', $this->crawler->filter('tbody > tr')->eq(1)->text());
        $this->assertContains('new new', $this->crawler->filter('tbody > tr')->eq(1)->text());
        $this->assertContains('00:05', $this->crawler->filter('tbody > tr')->eq(1)->text());
        $this->assertContains('00:10', $this->crawler->filter('tbody > tr')->eq(1)->text());
        $this->assertContains('/person/new?shift=', $this->crawler->filter('ol > li:nth-child(1) > a')->eq(1)->attr('href'));
    }

    public function testDeletePlan()
    {
        $link = $this->crawler->filter('.col-12 .pull-right')->link();
        $this->crawler = $this->client->click($link);
        $form = $this->crawler->filter('.btn-danger')->form();
        $this->client->submit($form);
        $this->crawler = $this->client->followRedirect();
        $this->assertContains('Warnung! Keine kommenden Schichtpläne', $this->crawler->filter('.alert')->text());
    }
}
