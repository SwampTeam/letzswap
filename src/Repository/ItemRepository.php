<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\ItemStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
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
        $sql = "SELECT i.id, i_s.time, s.label FROM status s
        inner JOIN item_status i_s on s.id = i_s.statuses_id
        inner JOIN item i on i_s.items_id = i.id
        WHERE s.label = 'active'
        ORDER BY i_s.time DESC";

        $qb = $this->_em->createQueryBuilder();
        $qb->select('i')
            ->from(Item::class, 'i')
            ->innerJoin('i.statuses', 'i_s', Expr\Join::ON)
            ->innerJoin('i_s.statuses', 's', Expr\Join::ON)
            ->where($qb->expr()->eq('s.label', ':active'))
            ->orderBy('i_s.time', 'DESC');
        $qb->setParameter('active', 'active');

        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            $itemsPerPage,
            [
                'distinct' => false
            ]
        );

        return $pagination;
    }
}