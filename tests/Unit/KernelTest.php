<?php

declare(strict_types=1);

/*
 *  This file is part of the Micro framework package.
 *
 *  (c) Stanislau Komar <kost@micro-php.net>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Micro\Framework\Kernel\Test\Unit;

use Micro\Component\DependencyInjection\Container;
use Micro\Framework\Kernel\AppModeEnum;
use Micro\Framework\Kernel\Kernel;
use Micro\Framework\Kernel\KernelInterface;
use Micro\Framework\Kernel\Plugin\PluginBootLoaderInterface;
use PHPUnit\Framework\TestCase;

require_once __DIR__.'/PluginClasses.php';

class KernelTest extends TestCase
{
    private KernelInterface $kernel;

    private Container $container;

    protected function setUp(): void
    {
        $plugins = [
            \PluginClassHasDependencies::class,
            \PluginClassDefault::class,
        ];

        $bootLoaders = [];
        for ($i = 0; $i < 3; ++$i) {
            $bootLoader = $this->createMock(PluginBootLoaderInterface::class);
            $bootLoader
                ->expects($this->any())
                ->method('boot');

            $bootLoaders[] = $bootLoader;
        }

        $this->kernel = new Kernel(
            $plugins,
            [],
            AppModeEnum::TEST,
        );

        $this->kernel->setBootLoaders($bootLoaders);
        $this->kernel->addBootLoader($this->createMock(PluginBootLoaderInterface::class));

        $this->kernel->run();
    }

    public function testExceptionWhenTryBootloaderInstallAfterKernelRun()
    {
        $this->expectException(\LogicException::class);
        $this->kernel->addBootLoader($this->createMock(PluginBootLoaderInterface::class));
    }

    public function testKernelPlugins()
    {
        foreach ($this->kernel->plugins(\PluginClassDefault::class) as $plugin) {
            $this->assertInstanceOf(\PluginClassDefault::class, $plugin);
        }
    }

    public function testRunAgain()
    {
        $kernel = $this->getMockBuilder(Kernel::class)
            ->enableOriginalConstructor()
            ->setConstructorArgs(
                [
                    [],
                    [],
                    AppModeEnum::TEST,
                ]
            )
            ->onlyMethods([
                'loadPlugins',
            ])
        ->getMock();

        $kernel
            ->expects($this->once())
            ->method('loadPlugins');

        $kernel->run();
        $kernel->run();
    }
}
