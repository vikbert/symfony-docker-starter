<?php

declare(strict_types = 1);

namespace App\Controller\OauthMock;

use App\Controller\Siam\SiamConstant;
use App\Security\Sso\SsoProvider;
use Exception;
use JetBrains\PhpStorm\Pure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class AuthzController extends AbstractController
{
    private const MOCK_AUTHZ_CODE = 'MOCK_AUTHZ_CODE';

    /**
     * @Route("/api/oauth/mock/authz", name="api_mock_authz", methods={"GET"})
     */
    public function __invoke(Request $request): Response
    {
        $this->assertParamsSet(
            $request->query,
            ['client_id', 'resourceServer', 'scope', 'redirect_uri', 'response_type']
        );

        $redirectUrl = $request->query->get('redirect_uri') . $this->getRedirectQueryParams();

        if ($request->query->has('node_env')) {
            return new JsonResponse(['redirectUri' => $redirectUrl]);
        }

        return new RedirectResponse($redirectUrl);
    }

    #[Pure]
    private function getRedirectQueryParams(): string
    {
        return sprintf('?code=%s&scope=%s', self::MOCK_AUTHZ_CODE, SiamConstant::SSO_SCOPE);
    }

    private function assertParamsSet(ParameterBag $parameterBag, array $keys): void
    {
        foreach ($keys as $key) {
            if (!$parameterBag->has($key)) {
                throw new Exception(sprintf('query param "%s" missing', $key));
            }
        }
    }
}
