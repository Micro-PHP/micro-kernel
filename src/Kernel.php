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

use Micro\Component\DependencyInjection\Autowire\AutowireHelperFactory;
use Micro\Component\DependencyInjection\Autowire\AutowireHelperFactoryInterface;
use Micro\Component\DependencyInjection\ContainerCompiled;
use Micro\Component\DependencyInjection\ContainerInterface;
use Micro\Component\DependencyInjection\Proxy\ProxyBuilder;
use Micro\Component\DependencyInjection\Proxy\ProxyBuilderInterface;
use Micro\Component\DependencyInjection\Proxy\ProxyBuilderProductionDecorator;
use Micro\Component\DependencyInjection\Proxy\ProxyClassContentFactoryInterface;
use Micro\Component\DependencyInjection\Proxy\ProxyClassNameGenerator;
use Micro\Component\DependencyInjection\Proxy\ProxyClassNameGeneratorInterface;
use Micro\Component\DependencyInjection\Proxy\ProxyFactory;
use Micro\Component\DependencyInjection\Proxy\ProxyFileManager;
use Micro\Component\DependencyInjection\Proxy\ProxyFileManagerInterface;
use Micro\Framework\Kernel\Plugin\PluginBootLoaderInterface;

/**
 * @psalm-suppress ClassMustBeFinal
 */
class Kernel implements KernelInterface
{
    private bool $isStarted;

    /**
     * @var object[]
     */
    private array $plugins;

    /**
     * @var class-string[]
     */
    private array $pluginsLoaded;

    private ContainerCompiled $container;

    /**
     * @param class-string[]              $applicationPluginCollection
     * @param PluginBootLoaderInterface[] $pluginBootLoaderCollection
     *
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(
        private readonly array $applicationPluginCollection,
        private array $pluginBootLoaderCollection,
        private readonly AppModeEnum $mode,
        private readonly string $proxyNamespace = 'Micro\Proxy',
        private readonly string $proxyFileDestination = 'var/proxy/micro_proxy.php',
    ) {
        $this->isStarted = false;

        $this->pluginsLoaded = [];
        $this->plugins = [];
        $this->container = $this->createDefaultContainer();
    }

    #[\Override]
    public function getMode(): AppModeEnum
    {
        return $this->mode;
    }

    #[\Override]
    public function addBootLoader(PluginBootLoaderInterface $bootLoader): self
    {
        if ($this->isStarted) {
            throw new \LogicException('Bootloaders must be installed before starting the kernel.');
        }

        $this->pluginBootLoaderCollection[] = $bootLoader;

        return $this;
    }

    #[\Override]
    public function setBootLoaders(iterable $bootLoaders): self
    {
        $this->pluginBootLoaderCollection = [];

        foreach ($bootLoaders as $loader) {
            $this->addBootLoader($loader);
        }

        return $this;
    }

    #[\Override]
    public function run(): void
    {
        if ($this->isStarted) {
            return;
        }

        $this->loadPlugins();
        $this->container->compile();
        $this->initializePlugins();
        $this->isStarted = true;
    }

    #[\Override]
    public function container(): ContainerInterface
    {
        return $this->container;
    }

    #[\Override]
    public function loadPlugin(string $applicationPluginClass): void
    {
        if (\in_array($applicationPluginClass, $this->pluginsLoaded, true)) {
            return;
        }
        $autowireHelper = $this->autowireHelperFactory()->create();
        $this->container->register($applicationPluginClass, $autowireHelper->autowire($applicationPluginClass));
        $this->pluginsLoaded[] = $applicationPluginClass;
    }

    protected function initializePlugins(): void
    {
        foreach ($this->pluginsLoaded as $pluginClass) {
            $this->initializePlugin($pluginClass);
        }
    }

    /**
     * @param class-string $pluginClass
     */
    protected function initializePlugin(string $pluginClass): void
    {
        $plugin = $this->container->get($pluginClass);
        foreach ($this->pluginBootLoaderCollection as $bootLoader) {
            $bootLoader->boot($plugin);
        }

        $this->plugins[] = $plugin;
    }

    #[\Override]
    public function plugins(?string $interfaceInherited = null): \Traversable
    {
        foreach ($this->plugins as $plugin) {
            if (null === $interfaceInherited || ($plugin instanceof $interfaceInherited)) {
                yield $plugin;
            }
        }
    }

    protected function loadPlugins(): void
    {
        foreach ($this->applicationPluginCollection as $applicationPlugin) {
            $this->loadPlugin($applicationPlugin);
        }
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    private function createDefaultContainer(): ContainerCompiled
    {
        $proxyClassNameGenerator = $this->createProxyClassNameGenerator();
        $proxyFileManager = $this->createProxyFileManager();
        $proxyClassContentFactory = $this->createProxyClassContentFactory();
        $classProxyBuilder = $this->createClassProxyBuilder($proxyClassContentFactory, $proxyFileManager);

        return new ContainerCompiled(
            $proxyClassNameGenerator,
            $classProxyBuilder
        );
    }

    private function createClassProxyBuilder(
        ProxyClassContentFactoryInterface $classContentFactory,
        ProxyFileManagerInterface $proxyFileManager,
    ): ProxyBuilderInterface {
        $proxyBuilder = new ProxyBuilder(
            $classContentFactory,
            $proxyFileManager,
            $this->proxyNamespace,
        );

        if (!$this->mode->isProd()) {
            return $proxyBuilder;
        }

        return new ProxyBuilderProductionDecorator(
            $proxyBuilder,
            $proxyFileManager,
        );
    }

    private function autowireHelperFactory(): AutowireHelperFactoryInterface
    {
        return new AutowireHelperFactory(
            $this->container,
        );
    }

    private function createProxyClassNameGenerator(): ProxyClassNameGeneratorInterface
    {
        return new ProxyClassNameGenerator(
            $this->proxyNamespace
        );
    }

    private function createProxyClassContentFactory(): ProxyClassContentFactoryInterface
    {
        return new ProxyFactory(
            $this->createProxyClassNameGenerator()
        );
    }

    private function createProxyFileManager(): ProxyFileManagerInterface
    {
        return new ProxyFileManager(
            $this->proxyFileDestination,
        );
    }
}
