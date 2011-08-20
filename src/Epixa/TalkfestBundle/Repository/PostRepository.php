<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Repository;

use Doctrine\ORM\EntityRepository,
    Doctrine\ORM\QueryBuilder,
    Epixa\TalkfestBundle\Entity\Category;

/**
 * Repository for data access logic related to post entities
 *
 * @category   Talkfest
 * @package    Repository
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class PostRepository extends EntityRepository
{
    /**
     * Gets the basic query builder for retrieving post entities
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getSelectQueryBuilder()
    {
        return $this->createQueryBuilder('post');
    }

    /**
     * Restricts the given query to only posts that are associated with the given category
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param \Epixa\TalkfestBundle\Entity\Category $category
     * @return void
     */
    public function restrictToCategory(QueryBuilder $qb, Category $category)
    {
        $qb->andWhere('post.category = :category');
        $qb->setParameter('category', $category);
    }

    /**
     * Restricts the given query to only posts that fall on the given page
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param integer $page
     * @param integer $max
     * @return void
     */
    public function restrictToPage(QueryBuilder $qb, $page, $max = 25)
    {
        $qb->setMaxResults($max);
        $qb->setFirstResult($max * ($page - 1));
    }
}