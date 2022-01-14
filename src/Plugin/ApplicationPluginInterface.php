<?php

namespace Micro\Framework\Kernel\Plugin;

use Micro\Component\DependencyInjection\Container;

interface ApplicationPluginInterface
{
    /**
     * @param  Container $container
     * @return void
     */
    public function provideDependencies(Container $container): void;

    /**
     * @return string
     */
    public function name(): string;
}
