<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
 * @Route("/", name="homepage")
 */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/about", name="about")
     */
    public function aboutAction(Request $request)
    {
        return $this->render('default/about.html.twig');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(Request $request)
    {

    }

    /**
     * @Route("/lost-plan-pass", name="recover_plan_password")
     */
    public function lostPlanPasswordAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('AppBundle\Form\RecoverPlanPasswordType');
        $form->handleRequest($request);

        $plan = $em->getRepository('AppBundle:Plan')->find($request->get('plan'));

        if ($form->isSubmitted() && $form->isValid()) {

            if($plan->getEmail() === $form->getData()['email']) {
                die;
            }
        }

        return $this->render('default/lost-plan-password.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

}
