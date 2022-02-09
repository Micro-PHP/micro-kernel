<?php

namespace Micro\Framework\Kernel\Configuration;

class PluginConfiguration implements PluginConfigurationInterface
{
    /**
     * @param ApplicationConfigurationInterface $configuration
     */
    public function __construct(protected ApplicationConfigurationInterface $configuration)
    {
    }

    /**
     * @param  string $list
     * @param  string $separator
     * @return string[]
     */
    protected function explodeStringToArray(string $list, string $separator = ','): array
    {
        if($separator === '') {
            return [$list];
        }

        $itemsColl = explode($separator, $list);

        return array_map('trim', $itemsColl);
    }
}
