# Micro Framework - The minimum kernel for application initialization.

### Requirements

PHP  >= 8.0.0

### How to use the library

Add the latest version of micro/kernel into your project by using Composer or manually:

__Using Composer (Recommended)__

Or require the package inside the composer.json of your project:
```
"require": {
    "micro/kernel": "^1"
},
```

### Example

After adding the library to your project, include the file autoload.php found in root of the library.
```html
include 'vendor/autoload.php';
```

#### Simple usage:

```php

use Micro\Framework\Kernel\Configuration\DefaultApplicationConfiguration;
use Micro\Framework\Kernel\Plugin\PluginDependedInterface;
use Micro\Framework\Kernel\Plugin\ApplicationPluginInterface;
use Micro\Component\DependencyInjection\Container;
use Micro\Framework\Kernel\Plugin\PluginBootLoaderInterface;
use Micro\Framework\Kernel\KernelBuilder;

// Create simple plugin
class TestPlugin implements PluginDependedInterface
{
    public function provideDependencies(Container $container): void
    {
        print_r('Provided dependencies');
    }
}

// Create Dependency provider boot loader
class DependencyProviderLoader implements PluginBootLoaderInterface
{

    public function __construct(private readonly Container $container)
    {
    }
    
    public function boot(ApplicationPluginInterface $applicationPlugin): void
    {
        $applicationPlugin->getDependedPlugins($this->container);
    }
}

$kernelBuilder = new KernelBuilder();
$container = new Container();
$configuration = new DefaultApplicationConfiguration(['APP_ENV' => 'dev']);
$kernel = $kernelBuilder
    ->setApplicationConfiguration($configuration)
    ->setContainer($container)
    ->setApplicationPlugins([
        TestPlugin::class
    ])
    ->addBootLoaders([
        new DependencyProviderLoader($container)
    ])
    ->build();
;

$kernel->run();
```

## License

[MIT](LICENSE)