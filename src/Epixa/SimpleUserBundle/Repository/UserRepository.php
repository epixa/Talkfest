<?php
/**
 * Epixa - SimpleUser
 */

namespace Epixa\SimpleUserBundle\Repository;

use Doctrine\ORM\EntityRepository,
    Doctrine\ORM\QueryBuilder;

/**
 * Repository for data access logic related to comment entities
 *
 * @category   SimpleUser
 * @package    Repository
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class UserRepository extends EntityRepository
{
    /**
     * Gets the basic query builder for retrieving user entities
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createSelectQueryBuilder()
    {
        return $this->createQueryBuilder('user');
    }

    /**
     * Restricts the given query to only users that match the given username
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param string $username
     * @return void
     */
    public function restrictToUsername(QueryBuilder $qb, $username)
    {
        $qb->andWhere('user.username = :username');
        $qb->setParameter('username', $username);
    }
}