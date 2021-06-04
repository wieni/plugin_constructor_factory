<?php

namespace Drupal\plugin_constructor_factory\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface as BasePluginInspectionInterface;

/**
 * Defines an interface for setting extra plugin arguments using setters
 */
interface PluginInspectionInterface extends BasePluginInspectionInterface
{
    public function setPluginId(string $value): void;

    public function setPluginDefinition(array $value): void;
}
