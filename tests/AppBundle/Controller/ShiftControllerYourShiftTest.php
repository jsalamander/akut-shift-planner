<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Test Shifts
 *
 * Class ShiftControllerYourShiftTest
 * @package Tests\AppBundle\Controller
 */
class ShiftControllerYourShiftTest extends WebTestCase
{

    private $crawler;

    private $client;

    private $fixtures;

    public function setUp() {
        $this->fixtures = $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadCompleteDataSet'
        ))->getReferenceRepository();

        $this->loginAs($this->fixtures->getReference('admin-user'), 'main');
        $this->client = $this->makeClient();
    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/plan/' . $this->fixtures->getReference('admin-plan')->getId());

    }

}
