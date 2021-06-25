<?php

declare(strict_types = 1);

namespace App\Controller\OauthMock;

use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class TokenController extends AbstractController
{
    /**
     * @Route("/api/oauth/mock/token", name="api_mock_token", methods={"POST"})
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
                'access_token' => 'eyJraWQiOiIxNjM0Mzg5ODI0NjQ3NzQ4NzMyNDU1NDI5NjgyNzA2NTEzMTEzOTEiLCJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJodHRwczovL2lkcC1xLnNjaHdhcnovbmlkcC9vYXV0aC9uYW0iLCJqdGkiOiIxMWM2OTEzYi05MDUwLTRkYzMtYjJhZi1lYWIxZDMzYmU5MmIiLCJhdWQiOiI5ZjU0YjQ5MC03OGFmLTQwODctODU4ZS05M2EyYjg3ZTQ4ZWMiLCJleHAiOjE1ODQ3MTIzMjMsImlhdCI6MTU4NDcwODcyMywibmJmIjoxNTg0NzA4NjkzLCJzdWIiOiI5YzkzNzcwMzBkNjMxYzQxOWE5ODljOTM3NzAzMGQ2MyIsIl9wdnQiOiJ1eWVkQzdBWmhSa2hxQkNXR3dqckJBL2ljSVNySUNKZlpuRWY5Vm1NdDYxUzZEWHJlNjh3cTZPUnlDWkRRZnRxM0dja2tYUXk4WTJydXNNS2NsSGlQTDl4RUxIZ2cxdWVDZDlEUUhJMHlHdGF3blBOdDhkRlk1SFdUbEplb20yWEJ6QUNlemJmUW5Tc1BsbUNqTUwwMFdLSFJPdWEyaXRoSUZpRy9BUWM0c01HVCt6S0FkYmxXbU5rZWJhVVdpaEt3Q28vMmxrSkY4R1hGNDJLU2Nvcm9EUkUzT0FLWitKYVZ6TWRSc3FHQWg3UkpIMzFyQVBuQk5BTVlOTXE1RlI1bWhWazRwMjJXSjVSQ1MxOUFsVmNmbmVFNzFCL0VkaGIxZHBPZkdNM3l6SjdXZzhhZmQxNDA2SDVqVFpqZmVzUXFBRVd1WDI3WHpTNUtRUWhQYlYwYmF0YkJXRzVnOVZTVWdqaDZHN3o1TTBFSHVmYS9wTWpnWEVHcUlSTGxzWXBIbDJvc1lJbkhieHYzTmJPeDRKMVBSVkpwclZuUEg4eWY1a3pXaDB3N0VTVy9RY1JrNnBwNzd3VDdYc3hmSE9oTGJFV1NQNmtFR3B6MjluSC8waU5RM3o4VGdEMXR6TDEydUtycHNIRFBvYmtvYkcxR3A1V1JWd2xsakRoM0d3UHBzYmpCa0JYQnpNMmQwbHNqdXNCWVdhT2xmVnlXTmZocWIwWVRFazk0SFlaQmNuZ2NqU29yWEs0TUdRZ2VpREIuMSIsInNjb3BlIjpbImNhcnNhbGVzIl0sIm1haWwiOiJNYXJjdXMuSGFldXNzbGVyQG1haWwuc2Nod2FyeiIsIndvcmtmb3JjZUlEIjoiMTAwMTc4ODAxNyIsIl90YXJnZXQiOiJJZGVudGl0eVByb3ZpZGVyUlNVRSJ9.d5L-6FngsV8UzQAQxSmZ3kSw-tmSNCV5TLq-Ityd7NO5MEDWKkagZ_gIIrcoH3eXI7toL_z_GlPep1THMCcWe5mr-jRgmw1WzSWQLWVc42XnI-htCyAVFYXIEJi-KJ26PewHo7nk6Eu3OqdYwWWhg6_iAzafvGzlyAyoXBtG2gkVA_qhEpOnzHvfV_Mmhls0xHUA7zF3biHEq1xV-_2HajTZVT7Tgp9uoMLb9XOopDayO6lv4wi20ZtOquHaE4tX8gPTakfiedL5c448aUpZbtNjrlowa2bY8Hzdz8zMxl75fZT2RfAu9oS8n_hOZ6m_Jz_OZeWNIz23X4oVx5KiGg',
                'token_type' => 'bearer',
                'expires_in' => 3599,
                'scope' => 'carsales',
            ]
        );
    }

    /**
     * @param array<int, string> $keys
     */
    private function assertParamsSet(ParameterBag $parameterBag, array $keys): void
    {
        foreach ($keys as $key) {
            if (!$parameterBag->has($key)) {
                throw new InvalidArgumentException(sprintf('request param "%s" missing', $key));
            }
        }
    }
}
