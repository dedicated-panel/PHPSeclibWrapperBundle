<?php

namespace Dedipanel\PHPSeclibWrapperBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class TwigExtensionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->has('twig')) {
            $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../Resources/config'));
            $loader->load('twig.yml');
        }
    }
}
