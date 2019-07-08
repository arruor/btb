<?php declare(strict_types=1);

namespace App\Controller;

use App\AbstractController;
use App\Dto\News as NewsDto;
use App\Entity\News;
use App\Form\CreateArticleForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class NewsController extends AbstractController
{
    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
        $this->repository = $this->getEntityManager()->getRepository(News::class);
    }

    public function list(): Response
    {
        $news = $this->getDoctrine()->getRepository(News::class)->findAll();

        return $this->render('news/list.html.twig', ['news' => $news]);
    }

    public function article(int $id): Response
    {
        try {
            $article = $this->getDoctrine()->getRepository(News::class)->findById($id);

            if ($article === null) {
                throw new NotFoundHttpException('Article not found.');
            }

            return $this->render('news/list.html.twig', ['news' => [new NewsDto($article)]]);
        } catch (NotFoundHttpException $e) {
            return new Response($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create(Request $request): Response
    {
        $form = $this->createForm(CreateArticleForm::class);

        try {
            $form->handleRequest($request);
        } catch (Throwable $e) {
            return $this->redirectToRoute('error');
        }

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                // TODO: Better parameter handling!
                $data = $request->request->all()['create_article_form'];
                $data['authorId'] = 2;
                $data['categoryId'] = 1;
                unset($data['_token'], $data['confirm']);

                return $this->redirectToRoute('list-article', ['id' => $this->getRepository()->add($data)]);
            } catch (Throwable $e) {
                return $this->redirectToRoute('error');
            }
        }

        return $this->render('news/create.html.twig', ['form' => $form->createView()]);
    }

    public function update(Request $request, int $id): Response
    {
        $article = $this->getDoctrine()->getRepository(News::class)->findById($id);
        $form = $this->createForm(CreateArticleForm::class, $article);

        try {
            $form->handleRequest($request);
        } catch (Throwable $e) {
            return $this->redirectToRoute('error');
        }

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                // TODO: Better parameter handling!
                $data = $request->request->all()['create_article_form'];
                $data['authorId'] = 2;
                $data['categoryId'] = 1;
                unset($data['_token'], $data['confirm']);

                $article = $this->getRepository()->update($id, $data);

                return $this->redirectToRoute('list-article', ['id' => $article->getId()]);
            } catch (Throwable $e) {
                return $this->redirectToRoute('error');
            }
        }

        return $this->render('news/create.html.twig', ['form' => $form->createView()]);
    }

    public function delete(): void
    {
    }
}
