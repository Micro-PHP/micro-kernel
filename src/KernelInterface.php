<?php

/*
 *  This file is part of the Micro framework package.
 *
 *  (c) Stanislau Komar <kost@micro-php.net>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Micro\Framework\Kernel;

use Micro\Component\DependencyInjection\Container;
use Micro\Component\DependencyInjection\ContainerInterface;
use Micro\Framework\Kernel\Plugin\PluginBootLoaderInterface;

/**
 * The kernel is needed for plugin management. A plugin can be any class object.
 */
interface KernelInterface
{
    /**
     * Application mode (dev, prod, test)
     *
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function getMode(): AppModeEnum;

    /**
     * Get service Dependency Injection Container.
     *
     * @api
     *
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function container(): ContainerInterface;

    /**
     * @throws \RuntimeException
     *
     * @psalm-suppress PossiblyUnusedMethod
     * @psalm-suppress PossiblyUnusedReturnValue
     */
    public function addBootLoader(PluginBootLoaderInterface $bootLoader): self;

    /**
     * @param iterable<PluginBootLoaderInterface> $bootLoaders
     *
     * @throws \RuntimeException
     *
     * @psalm-suppress PossiblyUnusedReturnValue
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function setBootLoaders(iterable $bootLoaders): self;

    /**
     * Run application.
     *
     * @api
     */
    public function run(): void;

    /**
     * @param class-string $applicationPluginClass
     */
    public function loadPlugin(string $applicationPluginClass): void;

    /**
     * Iterate plugins with the specified type.
     *
     * @template T of object
     *
     * @psalm-param class-string<T>|null $interfaceInherited if empty, each connected plugin will be iterated
     *
     * @return \Traversable<T|object> Application plugins iterator
     *
     * @api
     */
    public function plugins(?string $interfaceInherited = null): \Traversable;
}
