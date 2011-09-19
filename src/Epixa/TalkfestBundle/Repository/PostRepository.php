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
     * @return void
     */
    public function includeCategory(QueryBuilder $qb)
    {
        $qb->addSelect('category');
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
     * @param integer $max
     * @return void
     */
    public function restrictToPage(QueryBuilder $qb, $page, $max = 25)
    {
        $qb->setMaxResults($max);
        $qb->setFirstResult($max * ($page - 1));
    }

    /**
     * Restricts the given query to only posts that are accessible by the given user
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param \Epixa\TalkfestBundle\Entity\User $user
     * @return void
     */
    public function restrictToAccessible(QueryBuilder $qb, User $user)
    {
        $qb->leftJoin('category.groups', 'groups');

        $whereStr = 'groups.id is null';

        $groupParameters = array();
        foreach ($user->getGroups() as $group) {
            $groupId = (int)$group->getId();
            $groupParameters[$groupId] = ':group_' . $groupId;
        }

        if ($groupParameters) {
            $whereStr .= sprintf(' or groups.id in (%s)', implode(', ', $groupParameters));
            foreach ($groupParameters as $groupId => $value) {
                $qb->setParameter('group_' . $groupId, $groupId);
            }
        }

        $qb->andWhere($whereStr);
        $qb->groupBy('post.id');
    }
}