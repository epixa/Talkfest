<?php

namespace Epixa\SimpleUserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint,
    Symfony\Component\Validator\ConstraintValidator,
    Epixa\SimpleUserBundle\Entity\User,
    Epixa\SimpleUserBundle\Service\UserService;

class AuthenticationValidator extends ConstraintValidator
{
    /**
     * @var \Epixa\SimpleUserBundle\Service\UserService
     */
    protected $service;

    /**
     * Determines if the user with the given credentials is authentic
     * 
     * @throws \InvalidArgumentException If the provided $user is not a valid type
     * @param \Epixa\SimpleUserBundle\Entity\User $user
     * @param \Symfony\Component\Validator\Constraint $constraint
     * @return bool
     */
    public function isValid($user, Constraint $constraint)
    {
        if (!$user instanceof User) {
            throw new \InvalidArgumentException('User must be of type Epixa\SimpleUserBundle\Entity\User');
        }
        var_dump($user);
        die();

        /* @var \Epixa\SimpleUserBundle\Entity\User $user */
        if (!$this->getUserService()->authenticate($user)) {
            $this->setMessage($constraint->message);
            return false;
        }

        return true;
    }

    /**
     * Sets the user service
     * 
     * @param \Epixa\SimpleUserBundle\Service\UserService $service
     * @return AuthenticationValidator *Fluent interface*
     */
    public function setUserService(UserService $service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * Gets the user service
     *
     * @throws \RuntimeException If no user service has been set
     * @return \Epixa\SimpleUserBundle\Service\UserService
     */
    public function getUserService()
    {
        if (!$this->service) {
            throw new \RuntimeException('No user service configured');
        }

        return $this->service;
    }
}