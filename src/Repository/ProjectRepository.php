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

    public function findAllByNames(string $organizationName, string $repositoryName = null): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->andWhere($qb->expr()->eq('p.organizationName', ':organization_name'))
            ->orderBy('p.repositoryName')
            ->setParameter('organization_name', $organizationName);

        if ($repositoryName) {
            $qb
                ->andWhere($qb->expr()->eq('p.repositoryName', ':repository_name'))
                ->setParameter('repository_name', $repositoryName);
        }

        return $qb->getQuery()->getResult();
    }

    public function findAllOrganizationNames(): array
    {
        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();
        $stmt = $qb->distinct()->select('organization_name')->from('tbl_project')->execute();

        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function save(Project $gitProject): void
    {
        $this->getEntityManager()->persist($gitProject);
        $this->getEntityManager()->flush();
    }
}
