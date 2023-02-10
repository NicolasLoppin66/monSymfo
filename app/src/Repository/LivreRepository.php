<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 *
 * @method Livre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livre[]    findAll()
 * @method Livre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    public function save(Livre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Livre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function findAllOptimise(array $criteria = [], $limit = null,
                                        $offset = null) : array
    {
//        $db = $this->createQueryBuilder('l')
//            ->leftJoin("l.auteur", 'auteur')
//            ->addSelect('auteur')
//            ->leftJoin('l.categorie', 'categorie')
//            ->addSelect('categorie')
//            ->setFirstResult($offset)
//            ->setMaxResults($limit);
        $db = $this->findOptimise()
        ->orderBy('l.id', 'desc')
        ->setFirstResult($offset)
        ->setMaxResults($limit);
        return $db->getQuery()->getResult(); // retourner un tableau de valeur (objets)
    }

    public function findOptimiseDownOrUp($direction = 'down')
    {
        $db = $this->findOptimise();
        if ($direction == 'down'){
            $db->orderBy('l.dateParution', 'DESC');
        } else {
            $db->orderBy('l.dateParution', 'ASC');
        }
        return $db->getQuery()->getResult();
    }

    public function findOptimiseLastFive()
    {
        $db = $this->findOptimise();
        $db->orderBy('l.dateParution', 'DESC')
            ->groupBy('l.id')
            ->setMaxResults(5);
        return $db->getQuery()->getResult();
    }

    public function findOptimise()
    {
        return $this->createQueryBuilder('l')
            ->leftJoin("l.auteur", 'auteur')
            ->addSelect('auteur')
            ->leftJoin('l.categorie', 'categorie')
            ->addSelect('categorie');

    }

//    /**
//     * @return Livre[] Returns an array of Livre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Livre
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}