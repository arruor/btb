<?php declare(strict_types=1);

namespace App;

use App\Repository\AbstractRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractController;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractController extends BaseAbstractController
{
    protected const BASE_URL = 'http://btb.dev.fb/api/news/';

    protected $em;
    protected $repository;

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->em;
    }

    protected function getRepository(): AbstractRepository
    {
        return $this->repository;
    }

    protected function decodePayload(Request $request): array
    {
        return json_decode($request->getContent(), true);
    }
}
