<?php

declare(strict_types = 1);

namespace App\Controller\Sso;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class CheckController extends AbstractController
{
    /**
     * @Route("/api/sso/check", name="api_sso_check", methods={"GET"})
     */
    public function __invoke(Request $request): JsonResponse
    {
        $authzCode = $request->query->get('code');
        if (empty($authzCode)) {
            throw new BadRequestHttpException('Authz Code missing');
        }

        return new JsonResponse(['code' => $authzCode]);
    }
}
