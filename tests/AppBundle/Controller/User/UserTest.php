<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{
    public function testUser() {
        // load an empty db
        $this->loadFixtures(array());

        $client = $this->makeClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->filter('.btn-primary')->form(
            array(
                'fos_user_registration_form[email]' => 'yolo@styla.ch',
                'fos_user_registration_form[username]' => 'yolo',
                'fos_user_registration_form[plainPassword][first]' => '12341234',
                'fos_user_registration_form[plainPassword][second]' => '12341234'
            )
        );

        $client->submit($form);
        $crawler = $client->request('GET', '/profile');

        $link = $crawler->filter('.fos_user_user_show .btn-primary')->link();
        $crawler = $client->click($link);

        $form = $crawler->filter('.btn-primary')->eq(0)->form(
            array(
                'fos_user_profile_form[email]' => 'admin@admin.ch',
                'fos_user_profile_form[username]' => 'admin',
                'fos_user_profile_form[current_password]' => '12341234',
            )
        );
        $crawler = $client->submit($form);
        $this->assertContains('admin', $crawler->filter('.card-block')->text());

        $link = $crawler->filter('.fos_user_user_show .btn-primary')->link();
        $crawler = $client->click($link);

        $form = $crawler->filter('.btn-danger')->form();
        $crawler = $client->submit($form);
        $this->assertEquals('http://localhost/login', $crawler->getUri());

        $crawler = $client->request('GET', '/login');
        $form = $crawler->filter('.btn-primary')->eq(0)->form(
            array(
                '_username' => 'admin',
                '_password' => '12341234',
            )
        );

        $crawler = $client->submit($form);
        $this->assertContains('Fehlerhafte Zugangsdaten.', $crawler->filter('.alert')->text());

    }

    public function testUserLoginLogout() {
        // load an empty db
        $this->loadFixtures(array());

        $client = $this->makeClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->filter('.btn-primary')->form(
            array(
                'fos_user_registration_form[email]' => 'yolo@styla.ch',
                'fos_user_registration_form[username]' => 'yolo',
                'fos_user_registration_form[plainPassword][first]' => '12341234',
                'fos_user_registration_form[plainPassword][second]' => '12341234'
            )
        );

        $client->submit($form);
        $client->request('GET', '/logout');

        $crawler = $client->request('GET', '/login');
        $form = $crawler->filter('.btn-primary')->eq(0)->form(
            array(
                '_username' => 'yolo',
                '_password' => '12341234',
            )
        );

        $crawler = $client->submit($form);
        $this->assertEquals('http://localhost/plan/', $crawler->getUri());

        $crawler = $client->request('GET', '/logout');
        $this->assertEquals('http://localhost/', $crawler->getUri());
    }

}
