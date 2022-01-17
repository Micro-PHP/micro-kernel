<?php

namespace Micro\Framework\Kernel\Container;

use Micro\Component\DependencyInjection\Container;

interface ApplicationContainerFactoryInterface
{
    /**
     * @return Container
     */
    public function create(): Container;
}
