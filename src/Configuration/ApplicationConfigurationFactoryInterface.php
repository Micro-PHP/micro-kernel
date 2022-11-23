<?php

namespace Micro\Framework\Kernel\Configuration;

interface ApplicationConfigurationFactoryInterface
{
    /**
     * @return ApplicationConfigurationInterface
     */
    public function create(): ApplicationConfigurationInterface;
}
