<?php

declare(strict_types = 1);

namespace App\Controller\Sso;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class CheckController extends AbstractController
{
    /**
     * @Route("/api/sso/check", name="api_sso_check", methods={"GET"})
     */
    public function __invoke(Request $request, HttpClientInterface $httpClient): RedirectResponse
    {
        $authzCode = $request->query->get('code');
        if (empty($authzCode)) {
            throw new BadRequestHttpException('Authz Code missing');
        }

        $tokenResponse = $httpClient->request(
            'POST',
            $this->getInternalLoginUrl(),
            [
                'body' => ['code' => 'MOCK_AUTHZ_CODE'],
            ]
        );
        $token = $tokenResponse->toArray()['authToken'];
        $response = new RedirectResponse($this->generateUrl('app_profile'), 302);
        $response->headers->setCookie(new Cookie('authToken', $token));

        return $response;
    }

    private function getInternalLoginUrl(): string
    {
        return 'http://nginx' . $this->generateUrl('api_sso_login');
    }
}
