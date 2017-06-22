<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class PlanControllerTemplateTest extends WebTestCase
{
    public function setUp()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadTemplateData',
            'AppBundle\DataFixtures\ORM\LoadUserData'
        ));
    }

    public function testCreatePlanByTemplate() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/plan/new-by-template');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('.btn')->form(array(
            'appbundle_plan[templates]' => 0,
            'appbundle_plan[title]' => 'test template',
            'appbundle_plan[date]' => '2017-06-20',
            'appbundle_plan[description]' => 'some desc',
            'appbundle_plan[email]' => 'peter@test.ch',
            'appbundle_plan[password][first]' => '12345678',
            'appbundle_plan[password][second]' => '12345678',
        ));

        $client->submit($form);
        $this->assertEquals(0, $crawler->filter('.alert')->count());
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //test plan_show page
        $this->assertContains('test template', $crawler->filter('.justify-content-end')->text());
        $this->assertContains('20.06.2017', $crawler->filter('.justify-content-end')->text());
        $this->assertContains('some desc', $crawler->filter('blockquote')->text());
        $this->assertContains('meiu asdjffs', $crawler->filter('tr')->eq(1)->text());
        $this->assertContains('shift', $crawler->filter('tr')->eq(1)->text());
        $this->assertContains('00:01', $crawler->filter('tr')->eq(1)->text());
        $this->assertContains('00:02', $crawler->filter('tr')->eq(1)->text());
        $this->assertContains('#', $crawler->filter('#passwordPrompt')->attr('href'));
        $this->assertEquals(2, $crawler->filter('.container .text-nowrap')->count());
        $this->assertEquals(1, $crawler->filter('.modal-content')->count());
        $this->assertContains('/login_check', $crawler->filter('.modal-content form')->attr('action'));
    }

    public function testCreatePlanByTemplateWithError()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/plan/new-by-template');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('.btn')->form(array(
            'appbundle_plan[templates]' => 0,
            'appbundle_plan[title]' => 't',
            'appbundle_plan[date]' => '2017-06-20ASDFSADF',
            'appbundle_plan[description]' => 's',
            'appbundle_plan[email]' => 'petertest.ch',
            'appbundle_plan[password][first]' => '1234',
            'appbundle_plan[password][second]' => '12345678'
        ));

        $crawler = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(5, $crawler->filter('.alert')->count());
    }

    public function testDuplicateUserError()
    {

        $client = $this->createClient();
        $crawler = $client->request('GET', '/plan/new-by-template');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('.btn')->form(array(
            'appbundle_plan[templates]' => 0,
            'appbundle_plan[title]' => 't',
            'appbundle_plan[date]' => '2017-06-20ASDFSADF',
            'appbundle_plan[description]' => 's',
            'appbundle_plan[email]' => 'admin@admin.ch',
            'appbundle_plan[password][first]' => '1234',
            'appbundle_plan[password][second]' => '12345678'
        ));

        $crawler = $client->submit($form);
        $this->assertContains('The Email "admin@admin.ch" is already in use', $crawler->text());
    }

}
