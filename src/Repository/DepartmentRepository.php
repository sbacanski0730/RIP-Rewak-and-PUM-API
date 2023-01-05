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

    public function save(Department $entity): void
    {

        $this->getEntityManager()->persist($entity);
    }

    public function flush() {
        $this->getEntityManager()->flush();
    }

    public function deleteAll() {
        $this->createQueryBuilder('del')->delete()->where('1=1')->getQuery()->execute();
    }
    public function findAll()
    {
        return $this->findBy(array(), array('id' => 'ASC'));
    }

    public function findOneByName($name)
    {
        return $this->findOneBy(['name' => $name]);
    }
}
