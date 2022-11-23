<?php

namespace Micro\Framework\Kernel\Configuration;

use Micro\Framework\Kernel\Configuration\Exception\InvalidConfigurationException;

class DefaultApplicationConfiguration implements ApplicationConfigurationInterface
{
    private const BOOLEAN_TRUE = [
        'true', 'on', '1', 'yes'
    ];

    /**
     * @param array<string, mixed> $configuration
     */
    public function __construct(private array $configuration)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $key, $default = null, bool $nullable = true): mixed
    {
        if(is_bool($default)) {
            return $this->getBooleanValue($key, $default);
        }

        $value = $this->getValue($key, $default);

        if($nullable === false && !$value && !is_numeric($value)) {
            throw new InvalidConfigurationException(sprintf('Configuration key "%s" can not be NULL', $key));
        }

        return $value;
    }

    /**
     * @param string $key
     * @param bool $default
     *
     * @return bool
     */
    protected function getBooleanValue(string $key, bool $default): bool
    {
        $value = $this->getValue($key, $default);
        if($value === null) {
            return $default;
        }

        return in_array(mb_strtolower($value), self::BOOLEAN_TRUE, true);
    }

    /**
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    protected function getValue(string $key, mixed $default): mixed
    {
        return $this->configuration[$key] ?? $default;
    }
}
