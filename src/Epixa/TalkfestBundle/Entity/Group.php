<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Entity;

use FOS\UserBundle\Entity\Group as BaseGroup,
    Doctrine\ORM\Mapping as ORM,
    Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * A representation of a user group
 *
 * @category   Talkfest
 * @package    Entity
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 *
 * @ORM\Entity
 * @ORM\Table(name="talkfest_user_group")
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Group extends BaseGroup implements RoleInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", name="default_role")
     */
    protected $role;

    /**
     * Converts the group to a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Gets the default role for this group
     * 
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }
}