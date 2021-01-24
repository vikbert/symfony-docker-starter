<?php

declare(strict_types = 1);

namespace App\Controller\Oauth;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

final class GithubController extends AbstractController
{
    /**
     * @Route("/connect/github", name="connect_github", methods={"GET"})
     */
    public function connect(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry
            ->getClient('github')
            ->redirect(['public_profile', 'email'], []);
    }
}
