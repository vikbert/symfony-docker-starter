<?php

declare(strict_types = 1);

namespace App\Controller\Sso;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class CheckController extends AbstractController
{
    /**
     * @Route("/api/sso/check", name="sso_check", methods={"GET"})
     */
    public function __invoke(Request $request, HttpClientInterface $httpClient): JsonResponse
    {
        $authzCode = $request->query->get('code');
        if (empty($authzCode)) {
            throw new BadRequestHttpException('Authz Code missing');
        }
        
        $httpClient->request('POST', $this->ssoLoginUrl(), [
            'body' => ['code' => $authzCode],
        ]);
        
        return new JsonResponse();
    }

    private function ssoLoginUrl(): string
    {
        return $this->generateUrl('sso_login', [], UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
