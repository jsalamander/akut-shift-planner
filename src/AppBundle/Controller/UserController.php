<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\UserBundle\Controller\ProfileController;

/**
 * User controller.
 *
 * @Route("profile")
 */
class UserController extends ProfileController
{

    /**
     * Deletes a user entity.
     *
     * @Route("/delete", name="user_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request)
    {
        $UserManager = $this->container->get('fos_user.user_manager');
        $UserManager->deleteUser($this->getUser());

        return $this->redirectToRoute('fos_user_security_login');
    }
}
