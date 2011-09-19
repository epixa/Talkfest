<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Service;

use Doctrine\ORM\NoResultException,
    Symfony\Component\Security\Acl\Domain\ObjectIdentity,
    Symfony\Component\Security\Acl\Permission\MaskBuilder,
    Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity,
    Epixa\TalkfestBundle\Entity\Category,
    Epixa\TalkfestBundle\Entity\User,
    Epixa\TalkfestBundle\Model\CategoryDeletionOptions;

/**
 * Service for managing categories
 *
 * @category   Talkfest
 * @package    Service
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class CategoryService extends AbstractDoctrineService
{
    /**
     * Gets all categories in the system
     * 
     * @return \Epixa\TalkfestBundle\Entity\Category[]
     */
    public function getAll()
    {
        $repo = $this->getEntityManager()->getRepository('Epixa\TalkfestBundle\Entity\Category');
        return $repo->findAll();
    }

    /**
     * Gets a specific category by its unique identifier
     * 
     * @throws \Doctrine\ORM\NoResultException
     * @param integer $id
     * @return \Epixa\TalkfestBundle\Entity\Category
     */
    public function get($id)
    {
        $repo = $this->getEntityManager()->getRepository('Epixa\TalkfestBundle\Entity\Category');
        $category = $repo->find($id);
        if (!$category) {
            throw new NoResultException('That category cannot be found');
        }

        return $category;
    }

    /**
     * Adds the given category to the database
     * 
     * @param \Epixa\TalkfestBundle\Entity\Category $category
     * @return void
     */
    public function add(Category $category)
    {
        $em = $this->getEntityManager();

        $em->persist($category);
        $em->flush();
    }

    /**
     * Updates the given category in the database
     *
     * @throws \InvalidArgumentException
     * @param \Epixa\TalkfestBundle\Entity\Category $category
     * @return void
     */
    public function update(Category $category)
    {
        $em = $this->getEntityManager();
        if (!$em->contains($category)) {
            throw new \InvalidArgumentException('Category is not managed');
        }

        $em->persist($category);
        $em->flush();
    }

    /**
     * Deletes the given category from the database
     *
     * All posts associated with the deleted category are moved to an "inheriting" category that is defined
     * in the deletion options object.
     *
     * @throws \InvalidArgumentException|\RuntimeException
     * @param \Epixa\TalkfestBundle\Model\CategoryDeletionOptions $options
     * @return void
     */
    public function delete(CategoryDeletionOptions $options)
    {
        $inheritingCategory = $options->getInheritingCategory();
        $targetCategory = $options->getTargetCategory();
        
        /* @var \Doctrine\DBAL\Connection $db */
        $em = $this->getEntityManager();
        $db = $em->getConnection();

        $movePostsSql = sprintf(
            'update talkfest_post
             set category_id = %s
             where category_id = %s',
            $db->quote($inheritingCategory->getId()),
            $db->quote($targetCategory->getId())
        );

        $db->beginTransaction();
        try {
            $db->exec($movePostsSql);
            $em->remove($targetCategory);
            $em->flush();

            $db->commit();
        } catch (\Exception $e) {
            $db->rollback();
            throw new \RuntimeException('Transaction failed', null, $e);
        }
    }

    /**
     * Determines if the current user can access the given category
     *
     * @param \Epixa\TalkfestBundle\Entity\Category $category
     * @return bool
     */
    public function canAccess(Category $category)
    {
        /* @var \Epixa\TalkfestBundle\Entity\User $user */
        $user = $this->container->get('security.context')->getToken()->getUser();
        $categoryGroups = $category->getGroups();
        $userGroupIds = array();

        // if this category does not have any groups specified
        if (count($categoryGroups) === 0) {
            return true;
        }

        // if the user is not logged in
        if (!$user instanceof User) {
            return false;
        }

        foreach ($user->getGroups() as $group) {
            array_push($userGroupIds, $group->getId());
        }

        foreach ($categoryGroups as $group) {
            if (in_array($group->getId(), $userGroupIds)) {
                return true;
            }
        }

        return false;
    }
}