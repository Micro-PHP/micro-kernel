<?php

namespace Micro\Framework\Kernel\Plugin;

/**
 * An interface that allows you to declare plugin loading behavior. Called when the plugin is initialized.
 *
 * Do not use this interface unless absolutely necessary.
 *
 * <a href="https://github.com/Micro-PHP/kernel-bootloader-configuration/blob/master/src/Boot/ConfigurationProviderBootLoader.php">
 *      An example of the implementation of the loader to create an object with the plugin configuration.
 * </a>
 *
 * @api
 */
interface PluginBootLoaderInterface
{
    /**
     * Immediately after creation, a pre-configuration plugin gets here.
     *
     * @api
     *
     * @param  object $applicationPlugin
     *
     * @return void
     */
    public function boot(object $applicationPlugin): void;
}
