<?php

namespace Drupal\plugin_constructor_factory\Plugin;

use Drupal\Component\Plugin\ConfigurableInterface;

/**
 * Defines an interface for setting the plugin configuration using setters
 *
 * @see ConfigurableInterface
 */
trait PluginConfigurationTrait
{
    /**
     * Configuration information passed into the plugin.
     *
     * When using an interface like
     * \Drupal\Component\Plugin\ConfigurableInterface, this is where the
     * configuration should be stored.
     *
     * @var array
     */
    protected $configuration;

    public function defaultConfiguration()
    {
        return [];
    }

    public function getConfiguration()
    {
        return $this->configuration;
    }

    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }
}
