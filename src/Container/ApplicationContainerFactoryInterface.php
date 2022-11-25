<?php

namespace Micro\Framework\Kernel\Container;

use Psr\Container\ContainerInterface;

interface ApplicationContainerFactoryInterface
{
    /**
     * @return ContainerInterface
     */
    public function create(): ContainerInterface;
}
