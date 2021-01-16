<?php

declare(strict_types = 1);

namespace App\Controller\Sso;

use App\Security\SsoProvider;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class InfoController extends AbstractController
{
    private $clientRegistry;

    public function __construct(ClientRegistry $clientRegistry)
    {
        $this->clientRegistry = $clientRegistry;
    }

    /**
     * @Route("/api/sso/info", name="sso_info", methods={"GET"})
     */
    public function __invoke(): JsonResponse
    {
        $ssoAuthzUrl = $this->clientRegistry
            ->getClient('sso_client')
            ->getOAuth2Provider()
            ->getAuthorizationUrl(['scope' => SsoProvider::SSO_SCOPE]);

        return new JsonResponse(['authzUrl' => $ssoAuthzUrl]);
    }
}
