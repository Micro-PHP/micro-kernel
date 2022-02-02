<?php

namespace Micro\Framework\Kernel\Configuration;

interface ApplicationConfigurationInterface
{
    /**
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;
}
