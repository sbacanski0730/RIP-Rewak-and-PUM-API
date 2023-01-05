<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Room>
 *
 * @method Room|null find($id, $lockMode = null, $lockVersion = null)
 * @method Room|null findOneBy(array $criteria, array $orderBy = null)
 * @method Room[]    findAll()
 * @method Room[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function save(Room $entity): void
    {

        $this->getEntityManager()->persist($entity);
    }

    public function flush() {
        $this->getEntityManager()->flush();
    }

    public function deleteAll()
    {
        $this->createQueryBuilder('del')->delete()->where('1=1')->getQuery()->execute();
    }

    public function findOneByName($value): ?Room
    {
         return $this->createQueryBuilder('room')
                    ->where('room.name LIKE :val')
                    ->setParameter('val', $value)
                    ->orWhere('room.locationName LIKE :location')
                    ->setParameter('location', $value)
                    ->getQuery()->getOneOrNullResult();
    }

    public function findByName($value)
    {
         return $this->createQueryBuilder('room')
                    ->where('room.name LIKE :val')
                    ->setParameter('val', $value)
                    ->orWhere('room.locationName LIKE :location')
                    ->setParameter('location', $value)
                    ->getQuery()->execute();
    }

   


//    /**
//     * @return Room[] Returns an array of Room objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Room
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}