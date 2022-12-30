<?php

namespace App\Repository;

use App\Entity\Worker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Utils\CollegiumConnector;


/**
 * @extends ServiceEntityRepository<Worker>
 *
 * @method Worker|null find($id, $lockMode = null, $lockVersion = null)
 * @method Worker|null findOneBy(array $criteria, array $orderBy = null)
 * @method Worker[]    findAll()
 * @method Worker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Worker::class);
    }

    public function save(Worker $entity): void
    {

        $this->getEntityManager()->persist($entity);
    }

    public function flush() {
        $this->getEntityManager()->flush();
    }

    public function deleteAll() {
        $this->createQueryBuilder('del')->delete()->where('1=1')->getQuery()->execute();
    }

   public function findByName($value): ?Worker
   {
        $realName = str_replace("prof.", "", $value);
        $realName = str_replace("mgr.", "", $realName);
        $realName = str_replace(" mgr", "", $realName);
        $realName = str_replace("mgr ", "", $realName);
        $realName = str_replace("mgr inż.", "", $realName);
        $realName = str_replace("inż.", "", $realName);
        $realName = str_replace("dr n. med.", "", $realName);
        $realName = str_replace("dr hab. n. med.", "", $realName);
        $realName = str_replace("dr hab.", "", $realName);
        $realName = str_replace("dr.", "", $realName);
        $realName = str_replace("dr ", "", $realName);
        $realName = str_replace("lek.", "", $realName);
        $realName = trim($realName);


        $realName = str_replace("Małgorzata Jusiakowska - Piputa", "Jusiakowska - Piputa Małgorzata", $realName);

        $names = explode(" ", $realName);

        $query = $this->createQueryBuilder('w')
        ->andWhere('w.name LIKE :val')
        ->setParameter('val', '%'.$realName.'%');

        // echo $realName . "\r\n";
        // echo $names[1].' '.$names[0]."\r\n";

        if (count($names) > 1) {
            $query = $query->orWhere('w.name LIKE :val_reversed')
                ->setParameter('val_reversed', '%' . $names[1] . ' ' . $names[0] . '%');
        }

       return $query->getQuery()
        ->setMaxResults(1)->getOneOrNullResult();
   }

//    /**
//     * @return Worker[] Returns an array of Worker objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Worker
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
