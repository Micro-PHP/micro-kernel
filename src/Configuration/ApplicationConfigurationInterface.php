<?php

namespace Micro\Framework\Kernel\Configuration;

interface ApplicationConfigurationInterface
{
    /**
     * @param string $key
     * @param $default
     * @param bool $nullable
     * @return mixed
     */
    public function get(string $key, $default = null, bool $nullable = true): mixed;
}
