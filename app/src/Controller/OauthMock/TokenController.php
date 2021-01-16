<?php

declare(strict_types = 1);

namespace App\Controller\OauthMock;

use App\Security\SsoProvider;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class TokenController extends AbstractController
{
    /**
     * @Route("/api/oauth/mock/token", name="mock_token", methods={"POST"})
     */
    public function __invoke(Request $request): JsonResponse
    {
        $this->assertParamsSet(
            $request->request,
            [
                'client_id',
                'resourceServer',
                'scope',
                'redirect_uri',
                'grant_type',
                'client_secret',
                'code',
            ]
        );

        return new JsonResponse(
            [
                'access_token' => 'my_mocked_access_token',
                'token_type' => 'bearer',
                'expires_in' => 3599,
                'scope' => SsoProvider::SSO_SCOPE,
            ]
        );
    }

    private function assertParamsSet(ParameterBag $parameterBag, array $keys): void
    {
        foreach ($keys as $key) {
            if (!$parameterBag->has($key)) {
                throw new InvalidArgumentException(sprintf('query param "%s" missing', $key));
            }
        }
    }
}
