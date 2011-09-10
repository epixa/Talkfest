<?php
/**
 * Epixa - SimpleUser
 */

namespace Epixa\SimpleUserBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface,
    Doctrine\ORM\Mapping as ORM,
    Epixa\SimpleUserBundle\Validator\Constraints as SimpleUserAssert,
    Epixa\SimpleUserBundle\Hasher\Hasher;

/**
 * A representation of a user
 *
 * @category   SimpleUser
 * @package    Entity
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 *
 * @SimpleUserAssert\Authentication
 */
abstract class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="username", type="string")
     * @var string
     */
    protected $username;

    /**
     * @ORM\Column(name="email", type="string")
     * @var string
     */
    protected $email;

    /**
     * @ORM\Column(name="pass_hash", type="string", length="60")
     * @var string
     */
    protected $passHash;

    /**
     * @var \Epixa\SimpleUserBundle\Hasher\Hasher|null
     */
    protected $passwordHasher = null;


    /**
     * Gets the unique identifier for this entity
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the username
     * 
     * @param string $username
     * @return User *Fluent interface*
     */
    public function setUsername($username)
    {
        $this->username = (string)$username;
        return $this;
    }

    /**
     * Gets the username
     * 
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the email
     * 
     * @param string $email
     * @return User *Fluent interface*
     */
    public function setEmail($email)
    {
        $this->email = (string)$email;
        return $this;
    }

    /**
     * Gets the email
     * 
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Gets the password hash
     *
     * @return string
     */
    public function getPassHash()
    {
        return $this->passHash;
    }

    /**
     * Sets the user's password
     *
     * The password is hashed using the given hasher object and stored in passHash.
     * The plaintext password is never to be stored.
     * 
     * @param $password
     * @return User *Fluent interface*
     */
    public function setPassword($password)
    {
        if (!$this->passwordHasher) {
            throw new \RuntimeException('No password hasher configured');
        }

        $this->passHash = $this->passwordHasher->hash($password);
        return $this;
    }

    public function getPassword()
    {
        return '';
    }

    /**
     * Sets the hasher to be used for hashing the user password
     * 
     * @param \Epixa\SimpleUserBundle\Hasher\Hasher $hasher
     * @return User *Fluent interface*
     */
    public function setPasswordHasher(Hasher $hasher)
    {
        $this->passwordHasher = $hasher;
        return $this;
    }



    /**
     * Returns the roles granted to the user.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        return array();
    }

    /**
     * Returns the salt.
     *
     * @return string The salt
     */
    public function getSalt()
    {
        return 'blah';
    }

    /**
     * Removes sensitive data from the user.
     *
     * @return void
     */
    public function eraseCredentials()
    {

    }

    /**
     * The equality comparison should neither be done by referential equality
     * nor by comparing identities (i.e. getId() === getId()).
     *
     * However, you do not need to compare every attribute, but only those that
     * are relevant for assessing whether re-authentication is required.
     *
     * @param UserInterface $user
     * @return Boolean
     */
    public function equals(UserInterface $user)
    {
        var_dump($user);
        die('equals?');
    }
}