<?php

namespace App\Controller;

use App\Entity\PlanCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Plancollection controller.
 *
 * @Route("plancollection")
 */
class PlanCollectionController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /**
     * Lists all planCollection entities.
     *
     * @Route("/", name="plancollection_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');

        $queryBuilder = $em->getRepository('App:PlanCollection')->createQueryBuilder('p');
        $query = $queryBuilder
            ->where('p.user = :user')
            ->setParameter('user', $this->getUser()->getId())
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('plancollection/index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    /**
     * Creates a new planCollection entity.
     *
     * @Route("/new", name="plancollection_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $planCollection = new Plancollection();
        $form = $this->createForm('App\Form\PlanCollectionType', $planCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $planCollection->setUser($this->getUser());
            $em->persist($planCollection);
            $em->flush();

            return $this->redirectToRoute('plancollection_show', array('title' => $planCollection->getTitle()));
        }

        return $this->render('plancollection/new.html.twig', array(
            'planCollection' => $planCollection,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a planCollection entity.
     *
     * @Route("/{title}", name="plancollection_show")
     * @Method("GET")
     */
    public function showAction(PlanCollection $planCollection)
    {
        $deleteForm = $this->createDeleteForm($planCollection);

        return $this->render('plancollection/show.html.twig', array(
            'planCollection' => $planCollection,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing planCollection entity.
     *
     * @Route("/{title}/edit", name="plancollection_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PlanCollection $planCollection)
    {
        if ($planCollection->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can\'t delete this plan' );
        }

        $deleteForm = $this->createDeleteForm($planCollection);
        $editForm = $this->createForm('App\Form\PlanCollectionType', $planCollection);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('plancollection_show', array('title' => $planCollection->getTitle()));
        }

        return $this->render('plancollection/edit.html.twig', array(
            'planCollection' => $planCollection,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a planCollection entity.
     *
     * @Route("/{title}", name="plancollection_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PlanCollection $planCollection)
    {
        if ($planCollection->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('You can\'t delete this plan' );
        }

        $form = $this->createDeleteForm($planCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($planCollection);
            $em->flush();
        }

        return $this->redirectToRoute('plancollection_index');
    }

    /**
     * Creates a form to delete a planCollection entity.
     *
     * @param PlanCollection $planCollection The planCollection entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PlanCollection $planCollection)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('plancollection_delete', array('title' => $planCollection->getTitle())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
