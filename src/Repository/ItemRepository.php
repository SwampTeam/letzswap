<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findPaginated(Request $request, PaginatorInterface $paginator, int $itemsPerPage)
    {
        $sql = "SELECT i.id, i_s.time, s.label FROM item i, item_status i_s, status s
                inner JOIN item_status on s.id = item_status.statuses_id
                inner JOIN item on item_status.items_id = item.id
                WHERE s.label = 'active'
                GROUP BY i.id
                ORDER BY i_s.time DESC";

        $queryBuilder = $this->createQueryBuilder('p');
//        $queryBuilder->select('i.d, i_s.time')
//            ->from('Item', 'i')
//            ->where();

        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            $itemsPerPage
        );

        return $pagination;
    }
}