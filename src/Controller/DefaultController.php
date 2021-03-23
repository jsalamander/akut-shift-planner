<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
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
        $error = [];
        try {
            $response = \Unirest\Request::get($this->container->getParameter('repo_changelog'), $headers);

        } catch (Exception $e) {
            $error['msg'] = 'Could not load the release data. Check your internet connection or the api endpoint: ' .
            $this->container->getParameter('repo_changelog');
        }

        return $this->render('default/changelog.html.twig', [
            'releases' => $response->body,
            'error' => $error
        ]);
    }
}
