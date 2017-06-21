<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

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
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadPlanData'
        ));
        $this->assertTrue(true);
    }
}
