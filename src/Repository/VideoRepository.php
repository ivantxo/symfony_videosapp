<?php

namespace App\Repository;

use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Video>
 *
 * @method Video|null find($id, $lockMode = null, $lockVersion = null)
 * @method Video|null findOneBy(array $criteria, array $orderBy = null)
 * @method Video[]    findAll()
 * @method Video[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends ServiceEntityRepository
{
	private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Video::class);
		$this->paginator = $paginator;
    }

    public function add(Video $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Video $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

	public function findByChildIds(
		array $ids,
		int $page,
		?string $sort_method
	) {
		$sort_method = $sort_method !== 'rating' ? $sort_method : 'ASC';
		$dbQuery = $this->createQueryBuilder('v')
			->andWhere('v.category IN(:val)')
			->setParameter('val', $ids)
			->orderBy('v.title', $sort_method)
			->getQuery();
		return $this->paginator->paginate($dbQuery, $page, 5);
	}

	public function findByTitle(
		string $query,
		int $page,
		?string $sort_method
	) {
		$sort_method = $sort_method !== 'rating' ? $sort_method : 'ASC';
		$query_builder = $this->createQueryBuilder('v');
		$searchTerms = $this->prepare_query($query);
		foreach ($searchTerms as $key => $term) {
			$query_builder
				->orWhere('v.title LIKE :t_' . $key)
				->setParameter('t_' . $key, '%' . trim($term) . '%');
		}
		$db_query = $query_builder
			->orderBy('v.title', $sort_method)
			->getQuery();
		return $this->paginator->paginate($db_query, $page, 5);
	}

	public function prepare_query(string $query):array {
		return explode(' ', $query);
	}

//    /**
//     * @return Video[] Returns an array of Video objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Video
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
