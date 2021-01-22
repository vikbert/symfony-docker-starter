<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="app_profile", methods={"GET"})
     */
    public function __invoke(): Response
    {
        return $this->render('@templates/profile/index.html.twig');
    }
}
