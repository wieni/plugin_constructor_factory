<?php

namespace Drupal\plugin_constructor_factory;

use Drupal\Component\Plugin\PluginManagerBase;
use Drupal\plugin_constructor_factory\Plugin\Factory\ConstructorFactory;

/**
 * @see PluginManagerBase
 */
trait ConstructorFactoryPluginManagerTrait
{
    protected function getFactory()
    {
        if (!$this->factory) {
            $this->factory = new ConstructorFactory($this, $this->pluginInterface);
        }

        return $this->factory;
    }
}
