<?php
/**
 * Epixa - SimpleUser
 */

namespace Epixa\SimpleUserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader\YamlFileLoader,
    Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\Config\FileLocator;

/**
 * Exposes SimpleUser's services to the dependency injector
 *
 * @category   SimpleUser
 * @package    DependencyInjection
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class EpixaSimpleUserExtension extends Extension
{
    /**
     * Loads the bundle's config for dependency injection
     *
     * @param array $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);
        
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('config.yml');

        $container->setParameter('epixa_simple_user.model.user.class', $config['user_class']);
    }
}