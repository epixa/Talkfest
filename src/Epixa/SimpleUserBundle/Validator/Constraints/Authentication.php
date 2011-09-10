<?php

namespace Epixa\SimpleUserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Authentication extends Constraint
{
    public $message = 'The login credentials provided are not valid';

    /**
     * {@inheritDoc}
     * 
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * {@inheritDoc}
     * 
     * @return string
     */
    public function validatedBy()
    {
        return 'epixa_simple_user_validator_authentication';
    }
}