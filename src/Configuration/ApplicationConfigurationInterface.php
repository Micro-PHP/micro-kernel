<?php

namespace Micro\Framework\Kernel\Configuration;

interface ApplicationConfigurationInterface
{
    /**
     * @param  string $key
     * @param  $default
     * @return mixed
     */
    public function get(string $key, $default = null): mixed;
}
