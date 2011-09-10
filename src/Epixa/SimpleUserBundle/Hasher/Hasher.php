<?php
/**
 * Epixa - SimpleUser
 */

namespace Epixa\SimpleUserBundle\Hasher;

/**
 * A hasher is used to hash a given string
 * 
 * @category   SimpleUser
 * @package    Hasher
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
interface Hasher
{
    /**
     * Hashes the given string
     *
     * @abstract
     * @param string $string
     * @return void
     */
    public function hash($string);
}