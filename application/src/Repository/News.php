<?php declare(strict_types=1);

namespace App\Repository;

use App\Dto\News as NewsDto;
use App\Entity\AbstractEntity;
use App\Entity\News as NewsEntity;
use App\Entity\NewsCategory as CategoryEntity;
use App\Entity\User as UserEntity;
use App\Repository\NewsCategory as CategoryRepository;
use App\Repository\User as UserRepository;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class News extends AbstractRepository
{
    public function findAll(int $limit = 0): array
    {
        $news = [];
        $records = $this->getQueryBuilder()
            ->from(NewsEntity::class, 'n');
        if (!empty($limit)) {
            $records = $records->setMaxResults($limit);
        }

        $records = $records
            ->getQuery()
            ->getResult();

        if (!empty($records)) {
            foreach ($records as $record) {
                $news[] = new NewsDto($record);
            }
        }

        return $news;
    }

    public function findById(int $id): ?NewsEntity
    {
        $record = $this->getQueryBuilder()
            ->from(NewsEntity::class, 'n')
            ->where('n.id = :newsId')
            ->setParameter('newsId', $id)
            ->getQuery()
            ->getResult();

        if (!empty($record)) {
            // TODO: fix finer
            return $record[count($record) - 1];
        }

        return null;
    }

    public function add(array $data): int
    {
        $article = $this->save(new NewsEntity(), $data);

        if ($article === null) {
            throw new RuntimeException('Could not create article');
        }

        return $article->getId();

    }

    public function update(int $id, array $data): AbstractEntity
    {
        $article = $this->findById($id);

        if ($article === null) {
            throw new NotFoundHttpException('Article not found.');
        }

        return $this->save($article, $data);
    }

    public function delete(int $id): void
    {
        try {
            $article = $this->findById($id);

            if ($article === null) {
                throw new NotFoundHttpException('Article not found.');
            }

            $this->getEntityManager()->remove($article);
            $this->getEntityManager()->flush();
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage(), $e);
        } catch (Throwable $e) {
            throw new RuntimeException('Could not delete Article.');
        }
    }

    public function validate(array $data): bool
    {
        if (empty($data)) {
            return false;
        }

        if (empty($data['title'])) {
            return false;
        }

        if (empty($data['subTitle'])) {
            return false;
        }

        if (empty($data['body'])) {
            return false;
        }

        if (empty($data['authorId'])) {
            return false;
        }

        if (empty($data['categoryId'])) {
            return false;
        }

        return true;
    }

    private function save(NewsEntity $article, array $data): AbstractEntity
    {
        try {
            $article->setTitle($data['title']);
            $article->setSubTitle($data['subTitle']);
            $article->setBody($data['body']);
            $article->setAuthor($this->fetchUser($data['authorId']));
            $article->setCategory($this->fetchCategory($data['categoryId']));

            $this->getEntityManager()->persist($article);
            $this->getEntityManager()->flush();

            return $article;
        } catch (Throwable $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    private function fetchUser(int $id): UserEntity
    {
        $userRepository = new UserRepository(
            $this->getEntityManager(),
            $this->getEntityManager()->getClassMetadata(UserEntity::class)
        );
        $author = $userRepository->findById($id);

        if ($author === null) {
            throw new InvalidArgumentException('Author with ID {' . $id . '} not found.');
        }

        return $author;
    }

    private function fetchCategory(int $id): CategoryEntity
    {
        $categoryRepository = new CategoryRepository(
            $this->getEntityManager(),
            $this->getEntityManager()->getClassMetadata(CategoryEntity::class)
        );
        $category = $categoryRepository->findById($id);

        if ($categoryRepository === null) {
            throw new InvalidArgumentException('Category with ID {' . $id . '} not found.');
        }

        return $category;
    }
}
