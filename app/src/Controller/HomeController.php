<?php

namespace App\Controller;

use App\Security\Sso\SsoProvider;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(ClientRegistry $clientRegistry): Response
    {
        $ssoAuthzUrl = $clientRegistry
            ->getClient('sso_client')
            ->getOAuth2Provider()
            ->getAuthorizationUrl(['scope' => SsoProvider::SSO_SCOPE]);

        return $this->render(
            '@templates/home/index.html.twig',
            [
                'authzUrl' => $ssoAuthzUrl,
            ]
        );
    }
}
