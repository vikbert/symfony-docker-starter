<?php

namespace App\Controller;

use App\DataFixtures\AppFixtures;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile", methods={"GET"})
     */
    public function __invoke(UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneByToken(AppFixtures::TEST_TOKEN);

        if (null === $user) {
            throw new Exception('User not found by token');
        }

        return $this->render(
            '@templates/profile/index.html.twig',
            [
                'email' => $user->getEmail(),
            ]
        );
    }
}
