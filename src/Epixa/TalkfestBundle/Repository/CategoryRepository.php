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
 * Repository for data access logic related to category entities
 *
 * @category   Talkfest
 * @package    Repository
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class CategoryRepository extends EntityRepository
{
    /**
     * Gets the basic query builder for retrieving category entities
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getSelectQueryBuilder()
    {
        return $this->createQueryBuilder('category');
    }

    /**
     * Restricts the given query to only categories that do not match the given category
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param \Epixa\TalkfestBundle\Entity\Category $category
     * @return void
     */
    public function excludeCategory(QueryBuilder $qb, Category $category)
    {
        $qb->andWhere('category.id <> :category_id');
        $qb->setParameter('category_id', $category->getId());
    }

    /**
     * Restricts the given query to only categories that are accessible by the given user
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
        $qb->groupBy('category.id');
    }
}