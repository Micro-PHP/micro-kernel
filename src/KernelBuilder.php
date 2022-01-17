<?php

namespace Micro\Framework\Kernel;

use Micro\Component\DependencyInjection\Container;
use Micro\Framework\Kernel\Configuration\ApplicationConfigurationInterface;
use Micro\Framework\Kernel\Container\ApplicationContainerFactoryInterface;
use Micro\Framework\Kernel\Container\Impl\ApplicationContainerFactory;
use Micro\Framework\Kernel\Plugin\ApplicationPluginInterface;

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

    public function __construct()
    {
        $this->pluginCollection = [];
    }

    /**
     * @param array $applicationPluginCollection
     * @return $this
     */
    public function setApplicationPlugins(array $applicationPluginCollection): self
    {
        $this->pluginCollection = $applicationPluginCollection;

        return $this;
    }

    /**
     * @param ApplicationConfigurationInterface $configuration
     * @return $this
     */
    public function setApplicationConfiguration(ApplicationConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;

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
        return $this->createApplicationContainerFactory()->create();
    }

    /**
     * @return Kernel
     */
    public function build(): Kernel
    {
        return new Kernel(
            $this->pluginCollection,
            $this->configuration,
            $this->container()
        );
    }
}
