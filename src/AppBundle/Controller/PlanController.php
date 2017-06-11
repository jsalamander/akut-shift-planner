<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Plan;
use AppBundle\Entity\Shift;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

/**
 * Plan controller.
 *
 * @Route("plan")
 */
class PlanController extends Controller
{
    /**
     * Lists all plan entities.
     *
     * @Route("/", name="plan_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');

        $queryBuilder = $em->getRepository('AppBundle:Plan')->createQueryBuilder('p');

        $query = $queryBuilder
            ->where('p.isTemplate = false')
            ->andWhere('p.user =' . $this->getUser()->getId())
            ->orderBy('p.date', 'ASC')
            ->getQuery();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('plan/index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * Lists all template plans.
     *
     * @Route("/templates", name="plan_tempalte_index")
     * @Method("GET")
     */
    public function indexTemplateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $paginator  = $this->get('knp_paginator');

        $queryBuilder = $em->getRepository('AppBundle:Plan')->createQueryBuilder('p');

        $query = $queryBuilder
            ->where('p.isTemplate = true')
            ->andWhere('p.user =' . $this->getUser()->getId())
            ->orderBy('p.date', 'ASC')
            ->getQuery();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('plan/index-template.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new plan entity.
     *
     * @Route("/new", name="plan_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, SessionInterface $session, \Swift_Mailer $mailer)
    {
        $plan = new Plan();

        $form = $this->createForm('AppBundle\Form\PlanType', $plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $plan->setUser($this->getUser());
            $em->persist($plan);
            $em->flush();
            $session->set($plan->getId(), true);

            return $this->redirectToRoute('plan_show',array('id' => $plan->getId()));
        }

        return $this->render('plan/new.html.twig', array(
            'plan' => $plan,
            'form' => $form->createView()
        ));
    }

    /**
     * Creates a new plan by a template.
     *
     * @Route("/new-by-template", name="plan_new_by_template")
     * @Method({"GET", "POST"})
     */
    public function newByTemplateAction(Request $request, SessionInterface $session, \Swift_Mailer $mailer)
    {
        $em = $this->getDoctrine()->getManager();
        $plans = $em->getRepository('AppBundle:Plan')->findBy(
            array(
                'isTemplate' => true,
                'user' => $this->getUser()
            )
        );

        $classes = 'form-control';
        $form = $this->createFormBuilder()
            ->add('Templates', ChoiceType::class, array(
                'choices' => $plans,
                'choice_label' => function($plan, $key, $index) {
                    return $plan->getTitle();
                },
                'attr'  => array('class' => $classes),
                'label' => 'template_to_be_used'
            ))
            ->add('title', null, array(
                'attr'  => array('class' => $classes),
                'label' => 'new_title'
            )
            )->add('date', DateTimeType::class, array(
                'attr'  => array('class' => $classes . ' datepicker'),
                'html5' => false,
                'widget' => 'single_text',
                'label' => 'date'
            ))
            ->add('email', EmailType::class, array(
                'attr'  => array('class' => $classes),
                'required' => true,
                'label' => 'email_label'
            ))
            ->add('description', TextareaType::class, array(
                'attr'  => array('class' => $classes),
                'label' => 'description'
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plan = $form->getData()['Templates'];
            $title = $form->getData()['title'];
            $description = $form->getData()['description'];
            $date = $form->getData()['date'];
            $email = $form->getData()['email'];
            $password = bin2hex(random_bytes(15));

            $clone = clone $plan;
            $clone->setIsTemplate(false);
            $clone->setTitle($title);
            $clone->setDate($date);
            $clone->setDescription($description);
            $clone->setPassword($password);
            $clone->setEmail($email);

            $em->persist($clone);
            $em->flush();
            $session->set($clone->getId(), true);
            $this->sendPlanPasswordViaMail($mailer, $clone, $password);

            return $this->redirectToRoute('plan_show', array('id' => $clone->getId()));
        }


        return $this->render('plan/new-by-template.html.twig', array(
            'plans' => $plans,
            'form' => $form->createView()
        ));
    }

    /**
     * Finds and displays a plan entity.
     *
     * @Route("/{id}", name="plan_show")
     * @Method({"GET", "POST"})
     */
    public function showAction(Plan $plan, Request $request, SessionInterface $session)
    {
        $passwordForm = $this->createFormBuilder()
            ->add('password', PasswordType::class, array(
                'attr'  => array('class' => 'form-control'),
                'label' => 'password'
            ))
            ->getForm();

        $passwordForm->handleRequest($request);
        $deleteForm = $this->createDeleteForm($plan);

        $showDetails = false;
        if ($passwordForm->isSubmitted()) {
            $pw = $passwordForm->getData()['password'];
            if ($plan->getPassword() !== $pw ) {
                $passwordForm->get('password')->addError(new FormError('Wrong Password'));
            } elseif ($passwordForm->isValid() && $plan->getPassword() === $pw) {
                $showDetails = true;
                $session->set($plan->getId(), $plan->getPassword());
            }
        }

        return $this->render('plan/show.html.twig', array(
            'plan' => $plan,
            'delete_form' => $deleteForm->createView(),
            'password_form' => $passwordForm->createView(),
            'showDetails' => $showDetails
        ));
    }

    /**
     * Displays a form to edit an existing plan entity.
     *
     * @Route("/{id}/edit", name="plan_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Plan $plan)
    {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($plan);
        $editForm = $this->createForm('AppBundle\Form\PlanType', $plan);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();

            return $this->redirectToRoute('plan_show', array('id' => $plan->getId()));
        }

        return $this->render('plan/edit.html.twig', array(
            'plan' => $plan,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a plan entity.
     *
     * @Route("/{id}", name="plan_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Plan $plan)
    {
        $form = $this->createDeleteForm($plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            foreach ($plan->getShifts() as $shift) {
                $em->remove($shift);
                foreach ($shift->getPeople() as $person) {
                    $em->remove($person);
                }
            }

            $em->remove($plan);
            $em->flush();
        }

        return $this->redirectToRoute('plan_index');
    }

    /**
     * Creates a form to delete a plan entity.
     *
     * @param Plan $plan The plan entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Plan $plan)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('plan_delete', array('id' => $plan->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
