<?php

namespace Micro\Framework\Kernel\Tests\DummyDecoratorPlugin;

use Micro\Framework\Kernel\Tests\DummyServicePlugin\DummyServiceInterface;

final readonly class DummyDecoratorService implements DummyServiceInterface
{
    public function __construct(
        private DummyServiceInterface $decorated
    ) {
    }

    public function doNothing(): void
    {
        $this->decorated->doNothing();
    }
}