<?php declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;

/**
 * @method validate(array $decodePayload)
 * @method delete(int $id)
 * @method update(int $id, array $decodePayload)
 * @method add(array $decodePayload)
 */
abstract class AbstractRepository extends EntityRepository
{
    protected $queryBuilder;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->queryBuilder = $this->createQueryBuilder('qb');
    }

    public function getQueryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }
}
