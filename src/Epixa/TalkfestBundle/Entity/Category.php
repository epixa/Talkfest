<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    Symfony\Component\Validator\Constraints as Assert;

/**
 * A representation of a category
 *
 * @category   Talkfest
 * @package    Entity
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 *
 * @ORM\Entity(repositoryClass="Epixa\TalkfestBundle\Repository\CategoryRepository")
 * @ORM\Table(name="talkfest_category")
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", length="255")
     * @Assert\NotBlank()
     * @Assert\MinLength("4")
     * @Assert\MaxLength("255")
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(name="date_created", type="datetime")
     * @var \DateTime
     */
    protected $dateCreated;


    /**
     * Initializes a new Category
     *
     * The creation date is set to now and the topics collection is initialized.
     */
    public function __construct()
    {
        $this->setDateCreated('now');
    }

    /**
     * Gets the unique identifier for this entity
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the category name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the category name
     *
     * @param string $name
     * @return Category *Fluent interface*
     */
    public function setName($name)
    {
        $this->name = (string)$name;
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
     * @return Category *Fluent interface*
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
     * Converts the category to a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}