<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Entity;

use FOS\UserBundle\Entity\Group as BaseGroup,
    Doctrine\ORM\Mapping as ORM;

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
class Group extends BaseGroup
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean", name="is_default")
     * @var bool
     */
    protected $isDefault = false;

    /**
     * Sets whether this is the default group or not
     * 
     * @param bool $flag
     * @return Group
     */
    public function setIsDefault($flag)
    {
        $this->isDefault = (bool)$flag;
        return $this;
    }

    /**
     * Is this group the default group?
     * @return bool
     */
    public function isDefault()
    {
        return $this->isDefault;
    }
}