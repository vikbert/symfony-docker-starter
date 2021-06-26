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
     * @Route("/api/github/connect", name="api_github_connect", methods={"GET"})
     */
    public function connect(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry
            ->getClient('github')
            ->redirect(['public_profile', 'email'], []);
    }

    /**
     * @Route("/api/github/check", name="api_github_check", methods={"GET"})
     */
    public function check(): RedirectResponse
    {
        return new RedirectResponse($this->generateUrl('app_profile'), 302);
    }
}
