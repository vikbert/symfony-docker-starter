<?php

declare(strict_types = 1);

namespace App\Controller\Sso;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class CheckController extends AbstractController
{
    /**
     * @Route("/api/sso/check", name="api_sso_check", methods={"GET"})
     */
    public function __invoke(Request $request, HttpClientInterface $httpClient): RedirectResponse
    {
        return new RedirectResponse($this->generateUrl('app_profile'), 302);
    }
}
