<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Service;

use Doctrine\ORM\EntityManager,
    Symfony\Component\DependencyInjection\ContainerAware,
    Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Abstract service with access to a doctrine entity manager
 * 
 * @category   Talkfest
 * @package    Service
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
abstract class AbstractDoctrineService extends ContainerAware
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;


    /**
     * Constructor
     *
     * Sets the container for this service
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    /**
     * Sets the entity manager
     * 
     * @param  \Doctrine\ORM\EntityManager $em
     * @return AbstractDoctrineService *Fluent interface*
     */
    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
        return $this;
    }

    /**
     * Gets the doctrine entity manager
     *
     * If the entity manager is not configured, it is retrieved from the service container.
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        if (!$this->em) {
            if (!$this->container->has('doctrine')) {
                throw new \LogicException('Doctrine is not configured');
            }

            $this->em = $this->container->get('doctrine')->getEntityManager();
        }

        return $this->em;
    }
}