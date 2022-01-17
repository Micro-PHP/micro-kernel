<?php

namespace Micro\Framework\Kernel;

use Micro\Component\DependencyInjection\Container;
use Micro\Framework\Kernel\Plugin\ApplicationPluginInterface;

interface KernelInterface
{
    /**
     * Get service Dependency Injection Container
     *
     * @return Container
     */
    public function container(): Container;

    /**
     * Run application
     *
     * @return void
     */
    public function run(): void;

    /**
     * Terminate application
     *
     * @return void
     */
    public function terminate(): void;

    /**
     * @return ApplicationPluginInterface[]
     */
    public function plugins(): array;
}
