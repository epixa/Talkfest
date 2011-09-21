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
     * @var int
     */
    protected $totalPerPage = 100;


    /**
     * Sets the maximum number of posts to show on any given page
     *
     * @throws \InvalidArgumentException If $total is less than 1
     * @param $total
     * @return PostRepository *Fluent interface*
     */
    public function setTotalPerPage($total)
    {
        $total = (int)$total;
        if ($total < 1) {
            throw new \InvalidArgumentException('Total posts per page must be at least 1');
        }

        $this->totalPerPage = (int)$total;
        return $this;
    }

    /**
     * Gets the maximum number of posts to show on any given page
     *
     * @return int
     */
    public function getTotalPerPage()
    {
        return $this->totalPerPage;
    }

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
     * @return void
     */
    public function restrictToPage(QueryBuilder $qb, $page)
    {
        $max = $this->getTotalPerPage();
        $qb->setMaxResults($max);
        $qb->setFirstResult($max * ($page - 1));
    }
}