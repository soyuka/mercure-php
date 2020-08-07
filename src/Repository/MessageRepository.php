<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function getLastUpdated(?string $id)
    {
        if (!$id) {
            return null;
        }

        $lastMessage = $this->createQueryBuilder('u')
                    ->andWhere('u.id = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getOneOrNullResult();

        return $lastMessage ? $lastMessage->getCreatedAt() : null;
    }

    public function fetch(array $topics = [], array $options = []): array
    {
        $queryBuilder = $this->createQueryBuilder('u');

        if ($options['lastUpdated'] ?? false) {
            $queryBuilder->andWhere('u.createdAt > :createdAt')
            ->setParameter('createdAt', $options['lastUpdated']);
        }

        if ($topics) {
			$queryBuilder
			->andWhere(':topics MEMBER OF u.topics')
            ->setParameter('topics', $topics);
        }

        return 
			$queryBuilder
            ->getQuery()
            ->getResult()
        ;
    }
}
