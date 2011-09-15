<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
    FOS\UserBundle\Entity\User as BaseUser;

/**
 * A representation of a user
 *
 * @category   Talkfest
 * @package    Entity
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 *
 * @ORM\Entity
 * @ORM\Table(name="talkfest_user")
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class User extends BaseUser implements \FOS\UserBundle\Model\UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Epixa\TalkfestBundle\Entity\Group")
     * @ORM\JoinTable(name="talkfest_user_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * Gets the unique identifier for this entity
     * 
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}