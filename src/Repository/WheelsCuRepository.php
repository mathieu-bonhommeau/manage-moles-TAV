<?php

namespace App\Repository;

use App\Entity\WheelsCu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WheelsCu|null find($id, $lockMode = null, $lockVersion = null)
 * @method WheelsCu|null findOneBy(array $criteria, array $orderBy = null)
 * @method WheelsCu[]    findAll()
 * @method WheelsCu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WheelsCuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WheelsCu::class);
    }

    /**
    * @return WheelsCu Returns an array of WheelsCu objects
    */
    public function findWheelsByType($cuName, $wheelsCuType)
    {
        $result =  $this->createQueryBuilder('wcu')
            ->select('wcu', 'wcut', 'cu')
            ->leftJoin('wcu.wheelsCuType', 'wcut')
            ->leftJoin('wcut.cu', 'cu')
            ->andWhere('wcut.wheelsCuType = :wheelsCuType')
            ->andWhere('cu.name = :cuName')
            ->setParameter('wheelsCuType', $wheelsCuType)
            ->setParameter('cuName', $cuName)
            ->getQuery()
            ->getResult()
        ;
        return $result;
    }

    /**
    * @return WheelsCu Returns an array of WheelsCu objects
    */
    public function findAllWheelsCu()
    {
        $result =  $this->createQueryBuilder('wcu')
            ->select('wcu', 'wcut', 'cu')
            ->leftJoin('wcu.wheelsCuType', 'wcut')
            ->leftJoin('wcut.cu', 'cu')
            ->getQuery()
            ->getResult()
        ;
        
        return $result;
    }
}
