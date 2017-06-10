<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
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
    public function lostPlanPasswordAction(Request $request, \Swift_Mailer $mailer)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('AppBundle\Form\RecoverPlanPasswordType');
        $form->handleRequest($request);

        $plan = $em->getRepository('AppBundle:Plan')->find($request->get('plan'));
        $success = false;
        if($plan->getEmail() !== $form->getData()['email']) {
            $form->addError(new FormError('Wrong Email'));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->sendPlanPasswordViaMail($mailer, $plan, $plan->getPassword());
            $success = true;
        }

        return $this->render('default/lost-plan-password.html.twig',
            array(
                'success' => $success,
                'form' => $form->createView(),
                'plan' => $plan
            )
        );
    }

    /**
     * Send the plan password via email to the creator so they
     * can access the plan later
     *
     * @param \Swift_Mailer $mailer
     * @param $password
     */
    private function sendPlanPasswordViaMail(\Swift_Mailer $mailer, $plan, $password) {
        if (!$plan->getIsTemplate()) {
            $message = new \Swift_Message('Password Recovery');

            $message->setFrom('no-reply@schicht-plan.ch')
                ->setTo($plan->getEmail())
                ->setBody(
                    $this->renderView(
                        'email/plan-recover-password.html.twig',
                        array(
                            'password' => $password,
                            'plan_id' => $plan->getId()
                        )
                    ),
                    'text/html'
                )->addPart(
                    $this->renderView(
                        'email/plan-recover-password.txt.twig',
                        array(
                            'password' => $password,
                            'plan_id' => $plan->getId()
                        )
                    ),
                    'text/plain'
                );
            $mailer->send($message);
        }
    }

}
