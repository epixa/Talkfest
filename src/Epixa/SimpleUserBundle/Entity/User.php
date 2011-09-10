<?php
/**
 * Epixa - SimpleUser
 */

namespace Epixa\SimpleUserBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface,
    Doctrine\ORM\Mapping as ORM;

/**
 * A representation of a user
 *
 * @category   SimpleUser
 * @package    Entity
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
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
     * @ORM\Column(name="password", type="string", length="88")
     * @var string
     */
    protected $password;

    /**
     * @ORM\Column(name="salt", type="string", length="32")
     * @var string
     */
    protected $salt;


    /**
     * Constructs the user's unique password salt
     * 
     * @throws \RuntimeException
     */
    public function __construct()
    {
        $strong = false;
        $salt = base64_encode(openssl_random_pseudo_bytes(32, $strong));

        if ($salt === false) {
            throw new \RuntimeException('No salt could be determined');
        }

        if ($strong === false) {
            throw new \RuntimeException('The resulting hash was not created using a strong algorithm');
        }

        $this->salt = $salt;
    }

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
     * Sets the user's password hash
     *
     * @param $password
     * @return User *Fluent interface*
     */
    public function setPassword($password)
    {
        $this->password = (string)$password;
        return $this;
    }

    /**
     * Gets the user's password hash
     * 
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
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
     * Returns the password salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
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