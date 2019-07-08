<?php declare(strict_types=1);

namespace App\Controller;

use App\AbstractController;
use App\Entity\News;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    public function index(): Response
    {
        $news = $this->getDoctrine()->getRepository(News::class)->findAll(2);

        return $this->render('news/list.html.twig', ['news' => $news]);
    }

    public function ping(): JsonResponse
    {
        return new JsonResponse(['ping'=>'pong', 'ack' => time()]);
    }
}
