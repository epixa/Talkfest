<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\Form\Type;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilder;

/**
 * Form type for categories
 *
 * @category   Talkfest
 * @package    Form
 * @subpackage Type
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class CategoryType extends AbstractType
{
    /**
     * Instructs the construction of forms of this type
     * 
     * @param \Symfony\Component\Form\FormBuilder $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('name');
        $builder->add('groups', null, array(
            'label' => 'Groups that can access this category'
        ));
    }

    /**
     * Gets the default options to use for this form type
     * 
     * @param array $options
     * @return array
     */
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Epixa\TalkfestBundle\Entity\Category'
        );
    }

    /**
     * Gets the unique name of this form type
     * 
     * @return string
     */
    public function getName()
    {
        return 'talkfest_category';
    }
}