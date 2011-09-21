<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Repository;

use Doctrine\ORM\EntityRepository,
    Doctrine\ORM\QueryBuilder,
    Epixa\TalkfestBundle\Entity\Category,
    Epixa\TalkfestBundle\Entity\User;

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
     * @var int
     */
    protected $totalPerPage = 25;


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
     * Gets the basic query builder for retrieving post entities
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getSelectQueryBuilder()
    {
        return $this->createQueryBuilder('post');
    }

    /**
     * Includes the post's category details with the query
     * 
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param bool $returnCategoryDetails
     * @return void
     */
    public function includeCategory(QueryBuilder $qb, $returnCategoryDetails = true)
    {
        if ($returnCategoryDetails) {
            $qb->addSelect('category');
        }

        $qb->join('post.category', 'category');
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
     * @return void
     */
    public function restrictToPage(QueryBuilder $qb, $page)
    {
        $max = $this->getTotalPerPage();
        $qb->setMaxResults($max);
        $qb->setFirstResult($max * ($page - 1));
    }
}