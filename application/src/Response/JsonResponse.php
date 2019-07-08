<?php declare(strict_types=1);

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse as BaseJsonResnponse;

class JsonResponse extends BaseJsonResnponse
{
    public function __construct($data = null, int $status = 200, array $headers = [], bool $json = false)
    {
        parent::__construct($data, $status, $headers, $json);

        $this->setEncodingOptions( JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
