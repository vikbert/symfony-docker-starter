<?php

declare(strict_types = 1);

namespace App\Controller\Sso;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class LoginController extends AbstractController
{
    /**
     * @Route("/api/sso/login", name="api_sso_login", methods={"POST"})
     */
    public function __invoke(Request $request): JsonResponse
    {
        return new JsonResponse();
    }
}
