<?php

namespace Micro\Framework\Kernel\Configuration;

class PluginConfiguration implements PluginConfigurationInterface
{
    /**
     * @param ApplicationConfigurationInterface $configuration
     */
    public function __construct(protected readonly ApplicationConfigurationInterface $configuration)
    {
    }

    /**
     * @param  string|array $list
     * @param  string $separator
     * @return string[]
     */
    protected function explodeStringToArray(string|array $list, string $separator = ','): array
    {
        if(is_array($list)) {
            return $list;
        }

        if($separator === '') {
            return [$list];
        }

        $itemsColl = explode($separator, $list);

        return array_map('trim', $itemsColl);
    }
}
