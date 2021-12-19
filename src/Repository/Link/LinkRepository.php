<?php

namespace App\Repository\Link;

use App\Entity\IdentifierInterface;
use App\Entity\Link\Link;
use App\Repository\BaseRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Link|null find($id, $lockMode = null, $lockVersion = null)
 * @method Link|null findOneBy(array $criteria, array $orderBy = null)
 * @method Link[]    findAll()
 * @method Link[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Link::class);
    }

    /**
     * @throws ORMException
     */
    public function delete(IdentifierInterface $identifier): void
    {
        $this->_em->remove($identifier);
    }
}
