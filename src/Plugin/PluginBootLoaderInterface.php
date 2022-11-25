<?php

namespace Micro\Framework\Kernel\Plugin;

interface PluginBootLoaderInterface
{
    /**
     * @param  object $applicationPlugin
     *
     * @return void
     */
    public function boot(object $applicationPlugin): void;
}
