<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

final class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="app_profile", methods={"GET"})
     */
    public function __invoke(Security $security): Response
    {
        $this->addFlash('notice', 'Authentication ✅');

        return $this->render('@templates/profile/index.html.twig', ['user' => $security->getUser()]);
    }
}
