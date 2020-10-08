<?php

namespace App\Repository;

use App\Entity\Insight;
use App\Entity\InsightType;
use App\Entity\Journal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Insight|null find($id, $lockMode = null, $lockVersion = null)
 * @method Insight|null findOneBy(array $criteria, array $orderBy = null)
 * @method Insight[]    findAll()
 * @method Insight[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InsightRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Insight::class);
    }

    public function getLastByType(InsightType $type)
    {
        $rsm = $this->createResultSetMappingBuilder('i');
        $rsm->addJoinedEntityFromClassMetadata(Journal::class, 'j', 'i', 'journal', ['id' => 'j_id']);
        $select = $rsm->generateSelectClause();
        $nativeQuery = $this->_em->createNativeQuery(
            'SELECT '.$select.' 
                FROM insight i
                INNER JOIN journal j ON i.journal_id = j.id
                INNER JOIN insight_type it ON it.id = i.type_id
                INNER JOIN (
                    SELECT ii.journal_id as jId, MAX(ii.id) as maxId FROM insight ii WHERE ii.type_id = 1 GROUP BY jId
                ) s ON i.journal_id = s.jId and i.id = s.maxId
            WHERE i.type_id = :type_id ORDER BY CAST(value1 as FLOAT) DESC', $rsm);
        $nativeQuery->setParameter('type_id', $type->getId());
        return $nativeQuery->getResult();
    }

    // /**
    //  * @return Insight[] Returns an array of Insight objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Insight
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
