<?php

declare(strict_types=1);

/*
 *  This file is part of the Micro framework package.
 *
 *  (c) Stanislau Komar <kost@micro-php.net>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Micro\Framework\Kernel;

enum AppModeEnum: string
{
    case DEV = 'dev';
    case TEST = 'test';
    case PROD = 'prod';

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function isDev(): bool
    {
        return self::DEV === $this;
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function isTest(): bool
    {
        return self::TEST === $this;
    }

    public function isProd(): bool
    {
        return self::PROD === $this;
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public static function fromString(string $mode): self
    {
        $normalized = strtolower($mode);

        return match ($normalized) {
            'dev', 'development' => self::DEV,
            'test' => self::TEST,
            'prod', 'production' => self::PROD,
            default => throw new \InvalidArgumentException("Unknown app mode: {$mode}"),
        };
    }
}
