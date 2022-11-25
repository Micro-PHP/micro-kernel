<?php

namespace Micro\Framework\Kernel\Container\Impl;

use Micro\Component\DependencyInjection\Container;
use Micro\Framework\Kernel\Container\ApplicationContainerFactoryInterface;
use Psr\Container\ContainerInterface;

class ApplicationContainerFactory implements ApplicationContainerFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(): ContainerInterface
    {
        return new Container();
    }
}
