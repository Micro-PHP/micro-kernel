<?php

namespace Micro\Framework\Kernel;

use Micro\Component\DependencyInjection\Container;
use Micro\Framework\Kernel\Container\ApplicationContainerFactoryInterface;
use Micro\Framework\Kernel\Container\Impl\ApplicationContainerFactory;
use Micro\Framework\Kernel\Plugin\PluginBootLoaderInterface;
use Psr\Container\ContainerInterface;

class KernelBuilder
{
    /**
     * @var iterable<object>
     */
    private iterable $pluginCollection;

    /**
     * @var iterable<PluginBootLoaderInterface>
     */
    private iterable $bootLoaderPluginCollection;

    /**
     * @var Container|null
     */
    private ?Container $container;

    public function __construct()
    {
        $this->pluginCollection           = [];
        $this->bootLoaderPluginCollection = [];
        $this->container                  = null;
    }

    /**
     * @param  array $applicationPluginCollection
     *
     * @return $this
     */
    public function setApplicationPlugins(iterable $applicationPluginCollection): self
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
    public function addBootLoaders(iterable $bootLoaderCollection): self
    {
        foreach ($bootLoaderCollection as $bootLoader) {
            $this->addBootLoader($bootLoader);
        }

        return $this;
    }

    /**
     * @param  Container $container
     *
     * @return $this
     */
    public function setContainer(ContainerInterface $container): self
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
            $this->bootLoaderPluginCollection,
            $this->container(),
        );
    }
}
