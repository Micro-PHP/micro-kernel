<?php

namespace Micro\Framework\Kernel\Tests\Performance;

use Micro\Framework\BootDependency\Boot\DependencyProviderBootLoader;
use Micro\Framework\DependencyInjection\Container;
use Micro\Framework\Kernel\KernelBuilder;
use Micro\Framework\Kernel\Tests\DummyServicePlugin\DummyServicePlugin;
use PHPUnit\Framework\TestCase;

class KernelPerformanceTest extends TestCase
{
    public function testTime() {
        $plugins = [
            DummyServicePlugin::class,
        ];
        $count = 100000;
        for ($i = 0; $i < $count; $i++) {
            $className = "DynamicClass$i";
            eval("
                namespace DynamicNamespace {
                    use Micro\Framework\BootDependency\Plugin\DependencyProviderInterface;
                    use Micro\Framework\DependencyInjection\Container;
                    use Micro\Framework\Kernel\Tests\DummyDecoratorPlugin\DummyDecoratorService;
                    
                    class $className implements DependencyProviderInterface
                    {
                        public function provideDependencies(Container \$container): void
                        {
                            \$container->decorate(DummyServiceInterface::class, function (DummyServiceInterface \$decorated): DummyServiceInterface {
                                return new DummyDecoratorService(\$decorated);
                            });
                        }
                    }
                }
            ");
            $plugins[] = "DynamicNamespace\\$className";
        }
        // print_r($plugins);
        $container = new Container();
        $kernelBuilder = new KernelBuilder();
        $kernelBuilder->setContainer($container);
        $kernelBuilder->addBootLoader(new DependencyProviderBootLoader($container));
        $kernelBuilder->setApplicationPlugins($plugins);
        $kernel = $kernelBuilder->build();

        memory_reset_peak_usage();
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        $kernel->run();

        $endTime = microtime(true);
        $peakMemoryUsage = memory_get_peak_usage();
        $endMemory = memory_get_usage();

        $kernel->run();

        $executionTime = $endTime - $startTime;
        $memoryUsage = $endMemory - $startMemory;

        echo "Execution time: ".$executionTime." seconds\n";
        echo "Memory usage: ".$memoryUsage." bytes\n";
        echo "Memory peak usage: ".$peakMemoryUsage." bytes\n";

        $this->assertTrue(true);
    }
}
