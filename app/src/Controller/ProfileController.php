<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile", methods={"GET"})
     */
    public function __invoke(): Response
    {
        return $this->render('@templates/profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
}
