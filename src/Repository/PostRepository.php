<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\String\u;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findByMostRecent($limit): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Fonction utilisée par le composant Live src/Twig/Components/PostSearch.php
     * @return Post[]
     */
    public function findBySearchQuery(string $query): array
    {
        $searchTerms = $this->extractSearchTerms($query);
        $queryBuilder = $this->createQueryBuilder('p');

        //Si la recherche est vide, tout est affiché par défaut
        if (0 === \count($searchTerms)) {
            return $queryBuilder
            ->getQuery()
            ->getResult();
        }

        $this->whereByTerms($queryBuilder, 'title', $searchTerms);

        $result = $queryBuilder
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * Fonction utilisée par le composant Live src/Twig/Components/AdminPostSearch.php
     * @return Post[]
     */
    public function findByAdminSearchQuery(string $query, string $date): array
    {
        $searchTerms = $this->extractSearchTerms($query);
        $queryBuilder = $this->createQueryBuilder('p');

        //Si la recherche est vide, tout est affiché par défaut
        if (0 === \count($searchTerms)) {
            return $queryBuilder
            ->getQuery()
            ->getResult();
        }

        $this->whereByTerms($queryBuilder, 'title', $searchTerms);

        $result = $queryBuilder
            ->orderBy('p.createdAt', $date)
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * Transforme la chaine de caractères de la recherche en un tableau de termes de recherche
     * @return string[]
     */
    private function extractSearchTerms(string $searchQuery): array
    {
        $terms = array_unique(u($searchQuery)->replaceMatches('/[[:space:]]+/', ' ')->trim()->split(' '));
        
        return $terms;
    }

    private function whereByTerms(QueryBuilder $builder, string $field, array $terms): QueryBuilder
    {
        foreach ($terms as $key => $term) {
            $builder
                ->andWhere('p.'.$field.' LIKE :t_'.$key)
                ->setParameter('t_'.$key, '%'.$term.'%');
        }

        return $builder;
    }
}
