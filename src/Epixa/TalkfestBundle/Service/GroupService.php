<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Service;

use FOS\UserBundle\Entity\GroupManager,
    Symfony\Component\DependencyInjection\ContainerInterface,
    Symfony\Component\DependencyInjection\ContainerAwareInterface,
    Doctrine\ORM\EntityManager;

/**
 * Service for managing user groups
 *
 * @category   Talkfest
 * @package    Service
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class GroupService extends GroupManager implements ContainerAwareInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;


    /**
     * Constructs a new group service
     * 
     * @param \Doctrine\ORM\EntityManager $em
     * @param $class
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(EntityManager $em, $class, ContainerInterface $container)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);

        $metadata = $em->getClassMetadata($class);
        $this->class = $metadata->name;

        $this->setContainer($container);
    }

    /**
     * Sets the container for this service
     * 
     * @param null|\Symfony\Component\DependencyInjection\ContainerInterface $container
     * @return void
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}