<?php

namespace Drupal\plugin_constructor_factory\Plugin\Factory;

use Drupal\Component\Plugin\ConfigurableInterface;
use Drupal\Core\Plugin\Factory\ContainerFactory;
use Drupal\plugin_constructor_factory\Plugin\PluginInspectionInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Plugin factory which gets plugins from the container and sets
 * configuration, plugin ID & plugin definition using setters.
 */
class ConstructorFactory extends ContainerFactory
{
    public function createInstance($pluginId, array $configuration = [])
    {
        $pluginDefinition = $this->discovery->getDefinition($pluginId);
        $pluginClass = static::getPluginClass($pluginId, $pluginDefinition, $this->interface);

        if ($instance = $this->createInstanceWithoutInspectionInfo($pluginId)) {
            if ($instance instanceof PluginInspectionInterface) {
                $instance->setPluginId($pluginId);
                $instance->setPluginDefinition($pluginDefinition);
            }

            if ($instance instanceof ConfigurableInterface) {
                $instance->setConfiguration($configuration);
            }

            return $instance;
        }

        // If the plugin provides a factory method, pass the container to it.
        if (is_subclass_of($pluginClass, ContainerFactoryPluginInterface::class)) {
            return $pluginClass::create(\Drupal::getContainer(), $configuration, $pluginId, $pluginDefinition);
        }

        // Otherwise, create the plugin directly.
        return new $pluginClass($configuration, $pluginId, $pluginDefinition);

    }

    protected function createInstanceWithoutInspectionInfo(string $pluginId)
    {
        $pluginDefinition = $this->discovery->getDefinition($pluginId);
        $pluginClass = static::getPluginClass($pluginId, $pluginDefinition, $this->interface);

        // If the plugin has its service ID as annotation parameter, get it from the container
        if (!empty($pluginDefinition['service_id'])) {
            return \Drupal::getContainer()->get($pluginDefinition['service_id']);
        }

        // Try to create an instance using ClassResolver
        try {
            return \Drupal::classResolver()->getInstanceFromDefinition($pluginClass);
        } catch (\InvalidArgumentException $e) {
        }

        return null;
    }
}
