<?php

namespace Micro\Framework\Kernel\Tests\DummyServicePlugin;

use Micro\Framework\BootDependency\Plugin\DependencyProviderInterface;
use Micro\Framework\DependencyInjection\Container;

final readonly class DummyServicePlugin implements DependencyProviderInterface
{
    public function provideDependencies(Container $container): void
    {
        $container->register(DummyServiceInterface::class, function (): DummyServiceInterface {
            return new DummyService();
        });
    }
}