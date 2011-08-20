<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Service;

use Doctrine\ORM\EntityManager;

/**
 * Abstract service with access to a doctrine entity manager
 * 
 * @category   Talkfest
 * @package    Service
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
abstract class AbstractDoctrineService
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    
    /**
     * Sets the entity manager
     * 
     * @param  \Doctrine\ORM\EntityManager $em
     * @return AbstractDoctrineService *Fluent interface*
     */
    public function setEntityManager(EntityManager $em)
    {
        $this->entityManager = $em;
        return $this;
    }

    /**
     * Gets the doctrine entity manager
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}