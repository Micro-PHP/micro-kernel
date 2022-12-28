<?php

namespace Micro\Framework\Kernel;

use Micro\Component\DependencyInjection\Container;

/**
 * The kernel is needed for plugin management. A plugin can be any class object.
 *
 * <a href="https://github.com/Micro-PHP/micro-kernel/blob/master/src/Kernel.php" target="_blank"> Kernel implementation </a>
 *
 * <a href="https://github.com/Micro-PHP/micro-kernel" target="_blank"> GitHub Docs </a>
 *
 * <a href="https://packagist.org/packages/micro/kernel" target="_blank"> Packagist Repo </a>
 *
 * ```php
 * interface SomePluginInterface
 * {
 *      public function getName(): string;
 * }
 *
 * $kernel = new Kernel(
 *      [
 *          new class implements SomePluginInterface
 *          {
 *              public function getName(): string
 *              {
 *                  return 'SomePluginName';
 *              }
 *          }
 *      ],
 *      []
 * );
 *
 * $kernel->run();
 * $iterator = $kernel->plugins(SomePluginInterface::class);
 * foreach($iterator as $plugin)
 * {
 *      print_r($plugin->getName() . "\r\n");
 * }
 *
 * ```
 *
 * @api
 */
interface KernelInterface
{
    /**
     * Get service Dependency Injection Container
     *
     * @api
     *
     * @return Container
     */
    public function container(): Container;

    /**
     * Run application
     *
     * @api
     *
     * @return void
     */
    public function run(): void;

    /**
     * Terminate application
     *
     * @api
     *
     * @return void
     */
    public function terminate(): void;

    /**
     * @param  string $applicationPluginClass
     *
     * @return void
     */
    public function loadPlugin(string $applicationPluginClass): void;

    /**
     * Iterate plugins with the specified type.
     *
     * @param string|null $interfaceInherited If empty, each connected plugin will be iterated.
     *
     * @api
     *
     * @return iterable<object> Application plugins iterator
     */
    public function plugins(string $interfaceInherited = null): iterable;
}
