<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\User as UserEntity;

class User extends AbstractRepository
{
    public function findById(int $id): ?UserEntity
    {
        $user = null;

        $record = $this->getQueryBuilder()
            ->from(UserEntity::class, 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        if (!empty($record)) {
            $user = $record[0];
        }

        return $user;
    }
}
