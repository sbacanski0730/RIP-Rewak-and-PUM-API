<?php

namespace App\Repository;

use App\Entity\Department;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;


/**
 * @extends ServiceEntityRepository<Department>
 *
 * @method Department|null find($id, $lockMode = null, $lockVersion = null)
 * @method Department|null findOneBy(array $criteria, array $orderBy = null)
 * @method Department[]    findAll()
 * @method Department[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }

    // public function find($id, $lockMode = null, $lockVersion = null)
    // {
    //     return 222;
    // }

    // public function findAll()
    // {
    //     return 222;
    // }

    // public function findOneBy(array $criteria, ?array $orderBy = null)
    // {
    //     return 222;
    // }

    // public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
    // {
    //     return 222;
    // }

    // public function getClassName()
    // {
    //     return 222;
    // }
}