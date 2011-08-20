<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Repository;

use Doctrine\ORM\EntityRepository,
    Doctrine\ORM\QueryBuilder,
    Epixa\TalkfestBundle\Entity\Post;

/**
 * Repository for data access logic related to comment entities
 *
 * @category   Talkfest
 * @package    Repository
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class CommentRepository extends EntityRepository
{
    /**
     * Gets the basic query builder for retrieving comment entities
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getSelectQueryBuilder()
    {
        return $this->createQueryBuilder('comment');
    }

    /**
     * Restricts the given query to only comments that are associated with the given post
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param \Epixa\TalkfestBundle\Entity\Post $post
     * @return void
     */
    public function restrictToPost(QueryBuilder $qb, Post $post)
    {
        $qb->andWhere('comment.post = :post');
        $qb->setParameter('post', $post);
    }

    /**
     * Restricts the given query to only comments that fall on the given page
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param integer $page
     * @param integer $max
     * @return void
     */
    public function restrictToPage(QueryBuilder $qb, $page, $max = 50)
    {
        $qb->setMaxResults($max);
        $qb->setFirstResult($max * ($page - 1));
    }
}