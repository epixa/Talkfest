<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    Symfony\Component\Validator\Constraints as Assert;

/**
 * A representation of a comment
 *
 * @category   Talkfest
 * @package    Entity
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 * 
 * @ORM\Entity(repositoryClass="Epixa\TalkfestBundle\Repository\CommentRepository")
 * @ORM\Table(name="talkfest_comment")
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\MaxLength("5000")
     */
    protected $content;

    /**
     * Post of this comment
     *
     * @ORM\ManyToOne(targetEntity="Epixa\TalkfestBundle\Entity\Post")
     * @var Post
     */
    protected $post;

    /**
     * Author of the comment
     *
     * @ORM\ManyToOne(targetEntity="Epixa\TalkfestBundle\Entity\User")
     * @var User
     */
    protected $author;


    /**
     * Initializes a new Comment
     *
     * The comment's post and author is set.
     *
     * @param Post $post
     * @param User $author
     */
    public function __construct(Post $post, User $author)
    {
        $this->setPost($post);
        $this->setAuthor($author);
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
     * Sets the content
     *
     * @param string $content
     * @return Comment *Fluent interface*
     */
    public function setContent($content)
    {
        $this->content = (string)$content;
        return $this;
    }

    /**
     * Gets the content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets the post to which this comment belongs
     *
     * @param Post $post
     * @return Comment *Fluent interface*
     */
    public function setPost(Post $post)
    {
        $this->post = $post;
        return $this;
    }

    /**
     * Gets the post to which this comment belongs
     * 
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Sets the author of this comment
     *
     * @param User $author
     * @return Comment *Fluent interface*
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Gets the author of this comment
     * 
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }
}