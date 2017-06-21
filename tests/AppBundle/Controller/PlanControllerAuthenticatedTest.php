<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test Plan Controller from authenticated perspective
 *
 * Class PlanControllerTest
 * @package Tests\AppBundle\Controller
 */
class PlanControllerTestAuthenticated extends WebTestCase
{

    public function testIndex()
    {
        $this->client->request('GET', '/plan');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

    }

    public function testCreatePlan()
    {
    }

    public function testCreateWithoutShiftPlan()
    {
    }

    public function testCreatePlanErrors()
    {
    }
}
