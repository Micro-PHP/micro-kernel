<?php

namespace Micro\Framework\Kernel\Container\Impl;

use Micro\Component\DependencyInjection\Container;
use Micro\Framework\Kernel\Container\ApplicationContainerFactoryInterface;

class ApplicationContainerFactory implements ApplicationContainerFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(): Container
    {
        return new Container();
    }
}
