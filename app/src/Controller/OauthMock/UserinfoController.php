<?php

declare(strict_types = 1);

namespace App\Controller\OauthMock;

use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class UserinfoController extends AbstractController
{
    /**
     * @Route("/api/oauth/mock/userinfo", name="api_mock_userinfo", methods={"GET"})
     */
    public function __invoke(Request $request): JsonResponse
    {
        if (!$request->headers->has('Authorization')) {
            throw new InvalidArgumentException('header "Authorization" missing');
        }

        return new JsonResponse(
            [
                'mail' => 'vorname.nachname@mail.com',
                'sub' => 'sub name',
                'givenName' => 'first name',
                'sn' => 'last name',
                'claims' => [],
                'groups' => [
                    'sso-xx-test-revision',
                ],
                'workforceID' => '10003838738383',
            ]
        );
    }
}
