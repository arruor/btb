<?php declare(strict_types=1);

namespace App\Api\Controller;

use App\Dto\News as NewsDto;
use App\Entity\News;
use App\AbstractController;
use App\Response\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use InvalidArgumentException;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManagerInterface;

class NewsController extends AbstractController
{
    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
        $this->repository = $this->getEntityManager()->getRepository(News::class);
    }

    public function list(): JsonResponse
    {
        try {
            $news = $this->getDoctrine()->getRepository(News::class)->findAll();

            return new JsonResponse(
                $news,
                !empty($news) ? Response::HTTP_OK : Response::HTTP_NOT_FOUND
            );
        } catch (Throwable $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function article(int $id): JsonResponse
    {
        try {
            $article = $this->getDoctrine()->getRepository(News::class)->findById($id);

            if ($article === null) {
                throw new NotFoundHttpException('Article not found.');
            }

            return new JsonResponse(new NewsDto($article),Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create(Request $request): JsonResponse
    {
        try {
           $id = $this->getRepository()->add($this->decodePayload($request));

           return new JsonResponse(null, Response::HTTP_CREATED, ['Location' => self::BASE_URL . $id]);
        } catch (InvalidArgumentException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Throwable $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $article = $this->getRepository()->update($id, $this->decodePayload($request));

            return new JsonResponse(new NewsDto($article), Response::HTTP_OK);
        } catch (InvalidArgumentException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $this->getRepository()->delete($id);

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function validate(?int $id, Request $request): JsonResponse
    {
        try {
            if (!$this->getRepository()->validate($this->decodePayload($request))) {
                throw new InvalidArgumentException('Invalid data.');
            }

            return new JsonResponse(null, Response::HTTP_OK);
        } catch (InvalidArgumentException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Throwable $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
