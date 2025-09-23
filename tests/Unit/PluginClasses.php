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

readonly class PluginClassDefault
{
    public function run(): string
    {
        return __CLASS__;
    }
}

readonly class PluginClassHasDependencies
{
    public function __construct(
        private PluginClassDefault $default,
    ) {
    }

    public function run(): string
    {
        return $this->default->run().' '.__CLASS__;
    }
}
