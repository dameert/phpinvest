<?php

declare(strict_types=1);

namespace PhpInvest\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use PhpInvest\Entity\Project;

final class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function findAll(): ArrayCollection
    {
        $qb = $this->createQueryBuilder('p');
        $qb->orderBy('p.organizationName')->orderBy('p.repositoryName');

        return new ArrayCollection($qb->getQuery()->getResult());
    }

    public function findByName(string $name): ?Project
    {
        $qb = $this->createQueryBuilder('p');
        $project = $qb
            ->andWhere($qb->expr()->eq('p.name', ':name'))
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();

        return $project instanceof Project ? $project : null;
    }

    public function save(Project $gitProject): void
    {
        $this->getEntityManager()->persist($gitProject);
        $this->getEntityManager()->flush();
    }
}
