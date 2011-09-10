<?php
/**
 * Epixa - SimpleUser
 */

namespace Epixa\SimpleUserBundle\Hasher;

/**
 * A comparer is used to compare a given plaintext string with an existing hash
 *
 * @category   SimpleUser
 * @package    Hasher
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
interface Comparer
{
    /**
     * Compare the given hash to the string
     *
     * @abstract
     * @param string $hash
     * @param string $string
     * @return boolean
     */
    public function compare($hash, $string);
}