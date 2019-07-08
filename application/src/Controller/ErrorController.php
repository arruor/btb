<?php declare(strict_types=1);

namespace App\Controller;

use App\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends AbstractController
{
    public function error(): Response
    {
        return $this->render('error/error.html.twig');
    }

    public function notFound(): Response
    {
        return new Response(null, 404);
    }
}
