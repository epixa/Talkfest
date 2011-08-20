<?php
/**
 * Epixa - Talkfest
 */

namespace Epixa\TalkfestBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader\YamlFileLoader,
    Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\Config\FileLocator;

/**
 * Exposes Talkfest's services to the dependency injector
 *
 * @category   Talkfest
 * @package    DependencyInjection
 * @copyright  2011 epixa.com - Court Ewing
 * @license    Simplified BSD
 * @author     Court Ewing (court@epixa.com)
 */
class EpixaTalkfestExtension extends Extension
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
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('config.yml');
    }
}