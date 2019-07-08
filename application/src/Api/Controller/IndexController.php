<?php declare(strict_types=1);

namespace App\Api\Controller;

use App\AbstractController;
use App\Response\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    public function ping(): JsonResponse
    {
        return new JsonResponse(['ping' => 'pong', 'ack' => time()]);
    }

    public function notAllowed(): JsonResponse
    {
        return new JsonResponse(null, Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
