<?php

namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class PlanControllerTemplateTest extends WebTestCase
{

    use FixturesTrait;

    public function setUp()
    {
        $this->loadFixtures(array(
            'App\DataFixtures\LoadTemplateData',
            'App\DataFixtures\LoadUserData'
        ));
    }

    public function testCreatePlanByTemplate() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/plan/new-by-template');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertNotContains('private', $crawler->filter('select')->text());

        $form = $crawler->filter('.btn')->form(array(
            'App_plan[templates]' => 0,
            'App_plan[title]' => 'test template',
            'App_plan[date]' => '2099-06-20',
            'App_plan[description]' => 'some desc',
            'App_plan[email]' => 'peter@test.ch',
            'App_plan[password][first]' => '12345678',
            'App_plan[password][second]' => '12345678',
        ));

        $client->submit($form, []);
        $this->assertEquals(0, $crawler->filter('.alert')->count());
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //test plan_show page
        $this->assertSelectorTextContains('.justify-content-end', 'test template');
        $this->assertSelectorTextContains('.justify-content-end', '20.06.2099');
        $this->assertSelectorTextContains('blockquote', 'some desc');
        $this->assertContains('public shift', $crawler->filter('.card')->eq(0)->text());
        $this->assertContains('shift', $crawler->filter('.card')->eq(0)->text());
        $this->assertContains('00:01', $crawler->filter('.card')->eq(0)->text());
        $this->assertContains('00:02', $crawler->filter('.card')->eq(0)->text());
        $this->assertContains('#', $crawler->filter('#passwordPrompt')->attr('href'));
        $this->assertSelectorTextContains('.progress', '2');
        $this->assertEquals(1, $crawler->filter('.modal-content')->count());
        $this->assertContains('/login_check', $crawler->filter('.modal-content form')->attr('action'));
    }

    public function testCreatePlanByTemplateWithError()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/plan/new-by-template');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('.btn')->form(array(
            'App_plan[templates]' => 0,
            'App_plan[title]' => 't',
            'App_plan[date]' => '20asf17-0fsd6-20ASDFSADF',
            'App_plan[description]' => 's',
            'App_plan[email]' => 'petertest.ch',
            'App_plan[password][first]' => '1234',
            'App_plan[password][second]' => '12345678'
        ));

        $crawler = $client->submit($form, []);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(5, $crawler->filter('.alert')->count());
    }

    public function testDuplicateUserError()
    {

        $client = $this->createClient();
        $crawler = $client->request('GET', '/plan/new-by-template');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->filter('.btn')->form(array(
            'App_plan[templates]' => 0,
            'App_plan[title]' => 't',
            'App_plan[date]' => '2017-06-20ASDFSADF',
            'App_plan[description]' => 's',
            'App_plan[email]' => 'admin@admin.ch',
            'App_plan[password][first]' => '1234',
            'App_plan[password][second]' => '12345678'
        ));

        $crawler = $client->submit($form, []);
        $this->assertContains('The Email "admin@admin.ch" is already in use', $crawler->text());
    }

}
