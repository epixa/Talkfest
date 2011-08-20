<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Model;

use Epixa\TalkfestBundle\Entity\Category,
    Symfony\Component\Validator\Constraints as Assert;

/**
 * A representation of the parameters used to delete a category
 *
 * @category   Talkfest
 * @package    Model
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class CategoryDeletionOptions
{
    /**
     * @var \Epixa\TalkfestBundle\Entity\Category
     */
    protected $targetCategory;

    /**
     * @Assert\NotBlank()
     * @var \Epixa\TalkfestBundle\Entity\Category|null
     */
    protected $inheritingCategory = null;


    /**
     * Constructs the required deletion options
     *
     * The category that is targeted for deletion is set here to ensure it is never not set.
     *
     * @param \Epixa\TalkfestBundle\Entity\Category $category
     */
    public function __construct(Category $category)
    {
        if (!$category) {
            throw new \InvalidArgumentException('No target category specified');
        }

        $this->targetCategory = $category;
    }

    /**
     * Gets the category that is targeted for deletion
     * 
     * @return \Epixa\TalkfestBundle\Entity\Category
     */
    public function getTargetCategory()
    {
        return $this->targetCategory;
    }

    /**
     * Sets the category id that should inherit all posts from the deleted category
     *
     * @param \Epixa\TalkfestBundle\Entity\Category $category
     * @return CategoryDeletionOptions *Fluent interface*
     */
    public function setInheritingCategory($category)
    {
        if ($category->getId() === $this->getTargetCategory()->getId()) {
            throw new \InvalidArgumentException('A category targeted for deletion cannot be its own heir');
        }
        
        $this->inheritingCategory = $category;
        return $this;
    }

    /**
     * Gets the category id that should inherit all posts from the deleted category
     * 
     * @return \Epixa\TalkfestBundle\Entity\Category|null
     */
    public function getInheritingCategory()
    {
        return $this->inheritingCategory;
    }
}