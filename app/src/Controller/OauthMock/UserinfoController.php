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
     * @Route("/api/oauth/mock/userinfo", name="mock_userinfo", methods={"GET"})
     */
    public function __invoke(Request $request): JsonResponse
    {
        if (!$request->headers->has('Authorization')) {
            throw new InvalidArgumentException('header "Authorization" missing');
        }

        return new JsonResponse(
            [
                'sub' => '382ehud3h2398d23hdh',
                'mail' => 'vorname.nachname@oauth2.server',
                'givenName' => 'Vorname',
                'claims' => [],
                'groups' => [
                    'ssomoc-xx-test-revision',
                ],
                'sn' => 'Nachname',
                'workforceId' => '1234567890',
            ]
        );
    }
}
