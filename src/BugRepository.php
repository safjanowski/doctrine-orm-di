<?php

namespace App;

use Doctrine\ORM\EntityRepository;

class BugRepository extends EntityRepository
{
    /** @return Bug[] */
    public function getRecentBugs(int $number = 30): array
    {
        $dql = "SELECT b, e, r FROM App\Bug b JOIN b.engineer e JOIN b.reporter r ORDER BY b.created DESC";

        return $this->getEntityManager()->createQuery($dql)
            ->setMaxResults($number)
            ->getResult();
    }

    public function getRecentBugsArray(int $number = 30): array
    {
        $dql = "SELECT b, e, r, p FROM App\Bug b JOIN b.engineer e JOIN b.reporter r JOIN b.products p ORDER BY b.created DESC";

        return $this->getEntityManager()->createQuery($dql)
            ->setMaxResults($number)
            ->getArrayResult();
    }

    /** @return Bug[] */
    public function getUsersBugs(int $userId, int $number = 15): array
    {
        $dql = "SELECT b, e, r FROM App\Bug b JOIN b.engineer e JOIN b.reporter r WHERE b.status = 'OPEN' AND e.id = ?1 OR r.id = ?1 ORDER BY b.created DESC";

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter(1, $userId)
            ->setMaxResults($number)
            ->getResult();
    }

    public function getOpenBugsByProduct(): array
    {
        $dql = "SELECT p.id, p.name, count(b.id) AS openBugs FROM App\Bug b JOIN b.products p WHERE b.status = 'OPEN' GROUP BY p.id";

        return $this->getEntityManager()->createQuery($dql)
            ->getScalarResult();
    }
}
