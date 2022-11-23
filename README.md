>**:heavy_exclamation_mark: Basic container implementation with the ability to inject dependencies**

### Requirements

PHP  >= 8.0.0

### How to use the library

Add the latest version of MicroDependencyInjection into your project by using Composer or manually:

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

// Create simple plugin
class TestPlugin extends \Micro\Framework\Kernel\Plugin\AbstractPlugin
{
    public function provideDependencies(\Micro\Component\DependencyInjection\Container $container): void
    {
        print_r('Provided dependencies');
    }
}

// Create Dependency provider boot loader
class DependencyProviderLoader implements \Micro\Framework\Kernel\Plugin\PluginBootLoaderInterface
{

    public function __construct(private readonly \Micro\Component\DependencyInjection\Container $container)
    {
    }
    
    public function boot(\Micro\Framework\Kernel\Plugin\ApplicationPluginInterface $applicationPlugin): void
    {
        $applicationPlugin->provideDependencies($this->container);
    }
}

$kernelBuilder = new \Micro\Framework\Kernel\KernelBuilder();
$container = new \Micro\Component\DependencyInjection\Container();
$configuration = new \Micro\Framework\Kernel\Configuration\DefaultApplicationConfiguration(['APP_ENV' => 'dev']);
$kernel = $kernelBuilder
    ->setApplicationConfiguration($configuration)
    ->setContainer($container)
    ->setApplicationPlugins([
        TestPlugin::class
    ])
    ->setBootLoaders([
        new DependencyProviderLoader($container)
    ])
    ->build();
;

$kernel->run();
```

## License

[MIT](LICENSE)