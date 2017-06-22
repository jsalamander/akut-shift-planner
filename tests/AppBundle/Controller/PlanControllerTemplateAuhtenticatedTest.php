<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class PlanControllerTemplateAuthenticatedTest extends WebTestCase
{
    private $crawler;

    private $client;

    public function setUp()
    {
        $fixtures = $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadUserData',
            'AppBundle\DataFixtures\ORM\LoadTemplateData',
        ))->getReferenceRepository();

        $this->loginAs($fixtures->getReference('admin-user'), 'main');
        $this->client = $this->makeClient();
        $this->crawler = $this->client->request('GET', '/plan/new-by-template');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCreatePlanByTemplate() {

        $form = $this->crawler->filter('.btn')->form(array(
            'appbundle_plan[templates]' => 0,
            'appbundle_plan[title]' => 'test template',
            'appbundle_plan[date]' => '2017-06-20',
            'appbundle_plan[description]' => 'some desc'
        ));

        $this->client->submit($form);
        $this->assertEquals(0, $this->crawler->filter('.alert')->count());
        $this->crawler = $this->client->followRedirect();

        //test plan_show page
        $this->assertContains('test template', $this->crawler->filter('.justify-content-end')->text());
        $this->assertContains('20.06.2017', $this->crawler->filter('.justify-content-end')->text());
        $this->assertContains('some desc', $this->crawler->filter('blockquote')->text());
        $this->assertContains('meiu asdjffs', $this->crawler->filter('tr')->eq(1)->text());
        $this->assertContains('shift', $this->crawler->filter('tr')->eq(1)->text());
        $this->assertContains('00:01', $this->crawler->filter('tr')->eq(1)->text());
        $this->assertContains('00:02', $this->crawler->filter('tr')->eq(1)->text());
        $this->assertEquals(0, $this->crawler->filter('#passwordPrompt')->count());
        $this->assertEquals(2, $this->crawler->filter('.container .text-nowrap')->count());
    }

    public function testCreatePlanByTemplateWithError()
    {
        $form = $this->crawler->filter('.btn')->form(array(
            'appbundle_plan[templates]' => 0,
            'appbundle_plan[title]' => 't',
            'appbundle_plan[date]' => '2017-06-20ASDFSADF',
            'appbundle_plan[description]' => 's'
        ));

        $this->crawler = $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(3, $this->crawler->filter('.alert')->count());
    }
}
