<?php

namespace Micro\Framework\Kernel\Configuration;

interface ApplicationConfigurationInterface
{
    /**
     * @param string $key
     * @param mixed|null $default
     * @param bool $nullable
     *
     * @return mixed
     */
    public function get(string $key, mixed $default = null, bool $nullable = true): mixed;
}
