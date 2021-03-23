<?php

namespace Tests\App\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class PlanControllerTemplateAuthenticatedTest extends WebTestCase
{
    private $crawler;

    private $client;

    public function setUp()
    {
        $fixtures = $this->loadFixtures(array(
            'App\DataFixtures\ORM\LoadTemplateData',
        ))->getReferenceRepository();

        $this->loginAs($fixtures->getReference('admin-three-user'), 'main');
        $this->client = $this->makeClient();
        $this->crawler = $this->client->request('GET', '/plan/new-by-template');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCreatePlanByTemplate() {

        $form = $this->crawler->filter('.btn')->form(array(
            'App_plan[templates]' => 0,
            'App_plan[title]' => 'test template',
            'App_plan[date]' => '2099-06-20',
            'App_plan[description]' => 'some desc'
        ));

        $this->assertContains('public template', $this->crawler->filter('#App_plan_templates')->text());

        $this->client->submit($form);
        $this->assertEquals(0, $this->crawler->filter('.alert')->count());
        $this->crawler = $this->client->followRedirect();

        //test plan_show page
        $this->assertContains('test template', $this->crawler->filter('.justify-content-end')->text());
        $this->assertContains('20.06.2099', $this->crawler->filter('.justify-content-end')->text());
        $this->assertContains('some desc', $this->crawler->filter('blockquote')->text());
        $this->assertContains('meiu asdjffs', $this->crawler->filter('.card')->eq(0)->text());
        $this->assertContains('shift', $this->crawler->filter('.card')->eq(0)->text());
        $this->assertContains('00:01', $this->crawler->filter('.card')->eq(0)->text());
        $this->assertContains('00:02', $this->crawler->filter('.card')->eq(0)->text());
        $this->assertEquals(0, $this->crawler->filter('#passwordPrompt')->count());
        $this->assertContains('2', $this->crawler->filter('.progress')->text());

        //go to overview page
        $this->client->request('GET', '/plan');
        $this->crawler = $this->client->followRedirect();
        $this->assertContains('test template', $this->crawler->text());

        // make sure new created plan isn't marked as template
        $this->crawler = $this->client->request('GET', '/plan/new-by-template');
        $this->assertNotContains('test template', $this->crawler->filter('#App_plan_templates')->text());
    }

    public function testCreatePlanByTemplateWithError()
    {
        $form = $this->crawler->filter('.btn')->form(array(
            'App_plan[templates]' => 0,
            'App_plan[title]' => 't',
            'App_plan[date]' => '2001-06-20',
            'App_plan[description]' => 's'
        ));

        $this->crawler = $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(3, $this->crawler->filter('.alert')->count());
    }
}
