<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Shift;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Shift controller.
 *
 * @Route("shift")
 */
class ShiftController extends Controller
{
    /**
     * Lists all shift entities.
     *
     * @Route("/", name="shift_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $shifts = $em->getRepository('AppBundle:Shift')->findAll();

        return $this->render('shift/index.html.twig', array(
            'shifts' => $shifts,
        ));
    }

    /**
     * Creates a new shift entity.
     *
     * @Route("/new", name="shift_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $shift = new Shift();
        $form = $this->createForm('AppBundle\Form\ShiftType', $shift);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($shift);
            $em->flush();

            return $this->redirectToRoute('shift_show', array('id' => $shift->getId()));
        }

        return $this->render('shift/new.html.twig', array(
            'shift' => $shift,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a shift entity.
     *
     * @Route("/{id}", name="shift_show")
     * @Method("GET")
     */
    public function showAction(Shift $shift)
    {
        $deleteForm = $this->createDeleteForm($shift);

        return $this->render('shift/show.html.twig', array(
            'shift' => $shift,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing shift entity.
     *
     * @Route("/{id}/edit", name="shift_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Shift $shift)
    {
        $deleteForm = $this->createDeleteForm($shift);
        $editForm = $this->createForm('AppBundle\Form\ShiftType', $shift);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('shift_edit', array('id' => $shift->getId()));
        }

        return $this->render('shift/edit.html.twig', array(
            'shift' => $shift,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a shift entity.
     *
     * @Route("/{id}", name="shift_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Shift $shift)
    {
        $form = $this->createDeleteForm($shift);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($shift);
            $em->flush();
        }

        return $this->redirectToRoute('shift_index');
    }

    /**
     * Creates a form to delete a shift entity.
     *
     * @param Shift $shift The shift entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Shift $shift)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('shift_delete', array('id' => $shift->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
