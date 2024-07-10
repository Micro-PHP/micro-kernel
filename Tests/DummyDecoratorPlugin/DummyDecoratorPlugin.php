<?php

namespace Micro\Framework\Kernel\Tests\DummyDecoratorPlugin;

use Micro\Framework\BootDependency\Plugin\DependencyProviderInterface;
use Micro\Framework\DependencyInjection\Container;
use Micro\Framework\Kernel\Tests\DummyServicePlugin\DummyServiceInterface;

class DummyDecoratorPlugin implements DependencyProviderInterface
{
    public function provideDependencies(Container $container): void
    {
        $container->decorate(DummyServiceInterface::class, function (DummyServiceInterface $decorated): DummyServiceInterface {
            return new DummyDecoratorService($decorated);
        });
    }
}
