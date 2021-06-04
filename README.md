Plugin Constructor Factory
======================

[![Latest Stable Version](https://poser.pugx.org/wieni/plugin_constructor_factory/v/stable)](https://packagist.org/packages/wieni/plugin_constructor_factory)
[![Total Downloads](https://poser.pugx.org/wieni/plugin_constructor_factory/downloads)](https://packagist.org/packages/wieni/plugin_constructor_factory)
[![License](https://poser.pugx.org/wieni/plugin_constructor_factory/license)](https://packagist.org/packages/wieni/plugin_constructor_factory)

> Provides a plugin factory allowing you to inject dependencies using the constructor.

## Why?
By default, the only ways to inject dependencies, plugin inspection information and configuration into plugin classes are:
- by using `ContainerFactoryPluginInterface`
- by using the constructor, but only the plugin inspection information and configuration are passed as arguments

This imposes a couple of limitations:
- **Dependencies cannot be injected through the constructor.** This makes it impossible to use autowiring, something 
  which isn't possible by default in core, but which will inevitably be added in the future. 
- **Injecting plugin inspection information and configuration is required**, which makes **using `PluginBase` as base 
  class practically required.** In practice, this information is rarely needed. Having it injected should be optional.

## Installation
This package requires PHP 7.2 and Drupal 8.5 or higher. It can be installed using Composer:

```bash
 composer require wieni/plugin_constructor_factory
```

## How does it work?
By default, this plugin does nothing. It only provides the classes necessary to implement constructor injection in your 
plugins.

### Configuring plugin managers to use the factory
To make a plugin manager use `ConstructorFactory`, you need to override the `PluginManagerBase::getFactory` 
method. A trait is provided which does exactly this, [`PluginInspectionTrait`](src/Plugin/PluginInspectionTrait.php).

#### Example
```php
<?php

namespace Drupal\your_module;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceModifierInterface;
use Drupal\your_module\Plugin\Manager\ControllerPluginManager;

class YourModuleServiceProvider implements ServiceModifierInterface
{
    public function alter(ContainerBuilder $container): void
    {
        if ($container->hasDefinition('plugin.manager.wmcontroller.controller')) {
            $container->getDefinition('plugin.manager.wmcontroller.controller')
                ->setClass(ControllerPluginManager::class);
        }
    }
}
```

```php
<?php

namespace Drupal\your_module\Plugin\Manager;

use Drupal\plugin_constructor_factory\ConstructorFactoryPluginManagerTrait;
use Drupal\wmcontroller\ControllerPluginManager as ControllerPluginManagerBase;

class ControllerPluginManager extends ControllerPluginManagerBase
{
    use ConstructorFactoryPluginManagerTrait;
}
```

### Defining dependencies for your plugin classes
If you want to inject dependencies, add the plugin class to services.yml, add the service ID to the plugin annotation 
and inject your dependencies through the constructor.

```php
<?php

namespace Drupal\your_module\Controller\Node;

/**
 * @Controller(
 *     entity_type = "node",
 *     bundle = "homepage",
 *     service_id = "wmcustom.homepage",
 * )
 */
class HomepageController
{
}
```

If you have autowiring enabled, make sure there's a resource for the plugin namespace. Since the class name is the service ID, you don't need to add it to the annotation.

### Accessing plugin inspection information
If you need access to the `$pluginId` or `$pluginDefinition`, your class should implement 
[`PluginInspectionInterface`](src/Plugin/PluginInspectionInterface.php). This is the same as the core 
`PluginInspectionInterface`, but with added setters so that inspection information doesn't have to be injected through
the constructor. A common implementation for this interface is provided as [`PluginInspectionTrait`](src/Plugin/PluginInspectionTrait.php).

### Accessing plugin configuration
If you need access to the `$configuration`, your class should implement `Drupal\Component\Plugin\ConfigurableInterface`.
A common implementation for this interface is provided as 
[`PluginConfigurationTrait`](src/Plugin/PluginConfigurationTrait.php).

## Limitations
[`ConstructorFactory`](src/Plugin/Factory/ConstructorFactory.php) can only be used as a replacement for 
`ContainerFactory`. This means that if a plugin uses a custom factory, this will not work. Plugin managers extending 
`DefaultPluginManager` should be fine. 

## Changelog
All notable changes to this project will be documented in the
[CHANGELOG](CHANGELOG.md) file.

## Security
If you discover any security-related issues, please email
[security@wieni.be](mailto:security@wieni.be) instead of using the issue
tracker.

## License
Distributed under the MIT License. See the [LICENSE](LICENSE.md) file
for more information.
