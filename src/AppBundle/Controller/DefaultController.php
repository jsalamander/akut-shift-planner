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
     * @Route("/changelog", name="changelog")
     */
    public function changeLogAction(Request $request)
    {
        $headers = array('Accept' => 'application/json');
        $response = \Unirest\Request::get($this->container->getParameter('repo_changelog'), $headers);
        return $this->render('default/changelog.html.twig', [
            'releases' => $response->body
        ]);
    }
}
