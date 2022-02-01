<?php

namespace Micro\Framework\Kernel;

use Micro\Component\DependencyInjection\Container;
use Micro\Framework\Kernel\Configuration\ApplicationConfigurationInterface;
use Micro\Framework\Kernel\Container\ApplicationContainerFactoryInterface;
use Micro\Framework\Kernel\Container\Impl\ApplicationContainerFactory;
use Micro\Framework\Kernel\Plugin\ApplicationPluginInterface;
use Micro\Framework\Kernel\Plugin\PluginBootLoaderInterface;

class KernelBuilder
{
    /**
     * @var ApplicationPluginInterface[]
     */
    private array $pluginCollection;

    /**
     * @var ApplicationConfigurationInterface
     */
    private ApplicationConfigurationInterface $configuration;

    /**
     * @var PluginBootLoaderInterface[]
     */
    private array $bootLoaderPluginCollection;

    /**
     * @var Container
     */
    private Container $container;

    public function __construct()
    {
        $this->pluginCollection = [];
        $this->bootLoaderPluginCollection = [];
    }

    /**
     * @param  array $applicationPluginCollection
     * @return $this
     */
    public function setApplicationPlugins(array $applicationPluginCollection): self
    {
        $this->pluginCollection = $applicationPluginCollection;

        return $this;
    }

    /**
     * @param  PluginBootLoaderInterface $bootLoader
     * @return $this
     */
    public function addBootLoader(PluginBootLoaderInterface $bootLoader): self
    {
        $this->bootLoaderPluginCollection[] = $bootLoader;

        return $this;
    }

    /**
     * @param  PluginBootLoaderInterface[] $bootLoaderCollection
     * @return $this
     */
    public function setBootLoaders(array $bootLoaderCollection): self
    {
        foreach ($bootLoaderCollection as $bootLoader) {
            $this->addBootLoader($bootLoader);
        }

        return $this;
    }

    /**
     * @param  ApplicationConfigurationInterface $configuration
     * @return $this
     */
    public function setApplicationConfiguration(ApplicationConfigurationInterface $configuration): self
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * @param  Container $container
     * @return $this
     */
    public function setContainer(Container $container): self
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @return ApplicationContainerFactoryInterface
     */
    protected function createApplicationContainerFactory(): ApplicationContainerFactoryInterface
    {
        return new ApplicationContainerFactory();
    }

    /**
     * @return Container
     */
    protected function container(): Container
    {
        return $this->container ?? $this->createApplicationContainerFactory()->create();
    }

    /**
     * @return Kernel
     */
    public function build(): KernelInterface
    {
        return new Kernel(
            $this->pluginCollection,
            $this->configuration,
            $this->bootLoaderPluginCollection,
            $this->container(),
        );
    }
}
