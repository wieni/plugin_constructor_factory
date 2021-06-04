<?php

namespace Drupal\plugin_constructor_factory\Plugin;

use Drupal\Component\Plugin\ConfigurableInterface;
use Drupal\Component\Plugin\PluginBase;

/**
 * Defines an interface for setting extra plugin arguments using setters
 *
 * @see DerivativeInspectionInterface
 * @see PluginInspectionInterface
 */
trait PluginInspectionTrait
{
    /**
     * The plugin_id.
     *
     * @var string
     */
    protected $pluginId;

    /**
     * The plugin implementation definition.
     *
     * @var array
     */
    protected $pluginDefinition;

    public function getPluginId(): string
    {
        return $this->pluginId;
    }

    public function setPluginId(string $value): void
    {
        if (isset($this->pluginId)) {
            throw new \LogicException('Once set, plugin inspection information cannot be changed.');
        }

        $this->pluginId = $value;
    }

    public function getBaseId(): string
    {
        $plugin_id = $this->getPluginId();
        if (strpos($plugin_id, PluginBase::DERIVATIVE_SEPARATOR)) {
            [$plugin_id] = explode(PluginBase::DERIVATIVE_SEPARATOR, $plugin_id, 2);
        }

        return $plugin_id;
    }

    public function getDerivativeId(): string
    {
        $pluginId = $this->getPluginId();
        $derivativeId = null;
        if (strpos($pluginId, PluginBase::DERIVATIVE_SEPARATOR)) {
            [, $derivativeId] = explode(PluginBase::DERIVATIVE_SEPARATOR, $pluginId, 2);
        }

        return $derivativeId;
    }

    public function getPluginDefinition(): array
    {
        return $this->pluginDefinition;
    }

    public function setPluginDefinition(array $value): void
    {
        if (isset($this->pluginDefinition)) {
            throw new \LogicException('Once set, plugin inspection information cannot be changed.');
        }

        $this->pluginDefinition = $value;
    }

    /**
     * Determines if the plugin is configurable.
     *
     * @return bool
     *   A boolean indicating whether the plugin is configurable.
     */
    public function isConfigurable(): bool
    {
        return $this instanceof ConfigurableInterface;
    }
}
