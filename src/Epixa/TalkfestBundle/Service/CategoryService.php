<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Service;

use Doctrine\ORM\NoResultException,
    Epixa\TalkfestBundle\Entity\Category,
    Epixa\TalkfestBundle\Model\CategoryDeletionOptions,
    Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;

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
     * Gets a choice list of categories
     *
     * If a category is provided, it is not included in the returned by the choice list.
     *
     * @param \Epixa\TalkfestBundle\Entity\Category|null $excludedCategory
     * @return \Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList
     */
    public function getCategoryChoiceList(Category $excludedCategory = null)
    {
        /* @var \Epixa\TalkfestBundle\Repository\CategoryRepository $repo */
        $em = $this->getEntityManager();
        $repo = $em->getRepository('Epixa\TalkfestBundle\Entity\Category');
        $qb = $repo->getSelectQueryBuilder();
        $repo->excludeCategory($qb, $excludedCategory);


        return new EntityChoiceList($em, 'Epixa\TalkfestBundle\Entity\Category', null, $qb);
    }
}