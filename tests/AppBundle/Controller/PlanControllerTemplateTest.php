<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class PlanControllerTemplateTest extends WebTestCase
{
    public function setUp()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadTemplateData'
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

        $crawler = $client->submit($form);
        $this->assertEquals(0, $crawler->filter('.alert')->count());

        $client->followRedirect();
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());

        //test plan_show page
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('test template', $crawler->filter('.justify-content-end')->text());
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

}
