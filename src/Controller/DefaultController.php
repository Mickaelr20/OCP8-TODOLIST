<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    #[Route(path: '/', name: 'homepage')]
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }
}
