<?php
/**
 * Epixa - SimpleUser
 */

namespace Epixa\SimpleUserBundle\Service;

use Symfony\Component\Security\Core\User\UserProviderInterface,
    Symfony\Component\Security\Core\User\UserInterface,
    Symfony\Component\Security\Core\Exception\UsernameNotFoundException,
    Epixa\SimpleUserBundle\Entity\User,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\NoResultException;

/**
 * Service for managing users
 *
 * @category   SimpleUser
 * @package    Service
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class UserService implements UserProviderInterface
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;


    /**
     * @param \Doctrine\ORM\EntityManager $em
     * @param string $class
     */
    public function __construct(EntityManager $em, $class)
    {
        $this->setEntityManager($em);
        $this->class = $em->getClassMetadata($class)->name;
    }

    /**
     * Adds a new user to the system
     * 
     * @param \Epixa\SimpleUserBundle\Entity\User $user
     * @return void
     */
    public function add(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * Gets a specific user by its username
     *
     * @throws \Doctrine\ORM\NoResultException
     * @param string $username
     * @return \Epixa\UserBundle\Entity\User
     */
    public function getByUsername($username)
    {
        /* @var \Epixa\SimpleUserBundle\Repository\UserRepository $repo */
        $repo = $this->getEntityManager()->getRepository($this->class);
        $qb = $repo->createSelectQueryBuilder();

        $repo->restrictToUsername($qb, $username);

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @throws UsernameNotFoundException if the user is not found
     * @param string $username The username
     *
     * @return UserInterface
     */
    public function loadUserByUsername($username)
    {
        try {
            $user = $this->getByUsername($username);
        } catch (NoResultException $e) {
            throw new UsernameNotFoundException('That user does not exist');
        }

        return $user;
    }

    /**
     * Refreshes the user entity in the entity manager
     *
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        $this->em->refresh($user);
        return $user;
    }

    /**
     * {@inheritDoc}
     *
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === $this->getClass();
    }

    /**
     * Gets the user model's class name
     * 
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Sets the entity manager
     *
     * @param  \Doctrine\ORM\EntityManager $em
     * @return UserService *Fluent interface*
     */
    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
        return $this;
    }

    /**
     * Gets the doctrine entity manager
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }
}