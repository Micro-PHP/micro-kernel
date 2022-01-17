<?php

namespace Micro\Framework\Kernel\Plugin;

interface PluginBootLoaderInterface
{
    /**
     * @param ApplicationPluginInterface $applicationPlugin
     * @return void
     */
    public function boot(ApplicationPluginInterface $applicationPlugin): void;
}
