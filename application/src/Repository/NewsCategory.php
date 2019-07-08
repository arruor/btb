<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\NewsCategory as NewsCategoryEntity;

class NewsCategory extends AbstractRepository
{
    public function findById(int $id): ?NewsCategoryEntity
    {
        $category = null;

        $record = $this->getQueryBuilder()
            ->from(NewsCategoryEntity::class, 'nc')
            ->where('nc.id = :newsCategoryId')
            ->setParameter('newsCategoryId', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        if (!empty($record)) {
            $category = $record[0];
        }

        return $category;
    }
}
