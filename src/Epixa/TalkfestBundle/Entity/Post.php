<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    Symfony\Component\Validator\Constraints as Assert;

/**
 * A representation of a post
 *
 * @category   Talkfest
 * @package    Entity
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 *
 * @ORM\Entity(repositoryClass="Epixa\TalkfestBundle\Repository\PostRepository")
 * @ORM\Table(name="talkfest_post")
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(name="title", type="string")
     * @Assert\NotBlank()
     * @Assert\MaxLength("255")
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(name="comment", type="text", nullable="true")
     * @Assert\MaxLength("5000")
     * @var null|string
     */
    protected $comment = null;

    /**
     * @ORM\Column(name="date_created", type="datetime")
     * @var \DateTime
     */
    protected $dateCreated;

    /**
     * @ORM\ManyToOne(targetEntity="Epixa\TalkfestBundle\Entity\Category", inversedBy="posts")
     * @var Category
     */
    protected $category;


    /**
     * Initializes a new Post
     *
     * The creation date is set to now, and the category is set.
     *
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->setDateCreated('now');
        $this->setCategory($category);
    }

    /**
     * Gets the unique identifier of this entity
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the post title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the post title
     *
     * @param string $title
     * @return Post *Fluent interface*
     */
    public function setTitle($title)
    {
        $this->title = (string)$title;
        return $this;
    }

    /**
     * Gets the comment content associated with this post
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Sets the comment content associated with this post
     *
     * @param string|null $comment
     * @return Post *Fluent interface*
     */
    public function setComment($comment = null)
    {
        $comment = trim((string)$comment);
        if ($comment == '') {
            $comment = null;
        }

        $this->comment = $comment;
        return $this;
    }

    /**
     * Gets the date that this entity was created
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Sets the date this entity was created
     *
     * @throws \InvalidArgumentException
     * @param \DateTime|string|integer $date
     * @return StandardPost *Fluent interface*
     */
    public function setDateCreated($date)
    {
        if (is_string($date)) {
            $date = new \DateTime($date);
        } else if (is_int($date)) {
            $date = new \DateTime(sprintf('@%d', $date));
        } else if (!$date instanceof \DateTime) {
            throw new \InvalidArgumentException(sprintf(
                'Expecting string, integer or DateTime, but got `%s`',
                is_object($date) ? get_class($date) : gettype($date)
            ));
        }

        $this->dateCreated = $date;
        return $this;
    }

    /**
     * Gets the category of this post
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Sets the category of this post
     *
     * @param Category $category
     * @return Post *Fluent interface*
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
        return $this;
    }
}