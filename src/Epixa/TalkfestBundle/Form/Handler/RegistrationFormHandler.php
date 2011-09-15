<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Form\Handler;

use FOS\UserBundle\Form\Handler\RegistrationFormHandler as BaseRegistrationFormHandler,
    Symfony\Component\DependencyInjection\ContainerAwareInterface,
    Symfony\Component\DependencyInjection\ContainerInterface,
    FOS\UserBundle\Model\UserInterface as User,
    Doctrine\ORM\EntityManager;

/**
 * Form handler for user registration
 *
 * @category   Talkfest
 * @package    Form
 * @subpackage Handler
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class RegistrationFormHandler extends BaseRegistrationFormHandler implements ContainerAwareInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;


    /**
     * @param \Epixa\TalkfestBundle\Entity\User $user
     * @param $confirmation
     * @return void
     */
    protected function onSuccess(User $user, $confirmation)
    {
        $user->addGroup($this->getGroupService()->getDefaultGroup());
        parent::onSuccess($user, $confirmation);
    }

    /**
     * Sets the service container
     * 
     * @param null|\Symfony\Component\DependencyInjection\ContainerInterface $container
     * @return void
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Gets the doctrine entity manager
     *
     * @return \Epixa\TalkfestBundle\Service\GroupService
     */
    public function getGroupService()
    {
        return $this->container->get('fos_user.group_manager');
    }
}
