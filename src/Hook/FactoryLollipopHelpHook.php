<?php

namespace Drupal\factory_lollipop\Hook;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Hook implementation of hook_help() for the Factory Lollipop module.
 */
#[Hook('help')]
final class FactoryLollipopHelpHook {

  /**
   * Constructs a new FactoryLollipopHelpHook object.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   The module handler.
   * @param \Drupal\Core\Extension\ModuleExtensionList $moduleExtensionList
   *   The module extension list.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The configuration factory.
   * @param \Drupal\Component\Plugin\PluginManagerInterface|null $filterManager
   *   The filter plugin manager, NULL when the Filter module is not installed.
   */
  public function __construct(
    private readonly ModuleHandlerInterface $moduleHandler,
    private readonly ModuleExtensionList $moduleExtensionList,
    private readonly ConfigFactoryInterface $configFactory,
    private readonly ?PluginManagerInterface $filterManager = NULL,
  ) {
  }

  /**
   * Render the module README as help page.
   *
   * @param string $route_name
   *   The router name the help is being requested for.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The current route match.
   *
   * @return string|null
   *   The help text, or NULL when the route is not handled.
   */
  public function __invoke(string $route_name, RouteMatchInterface $route_match): ?string {
    if ($route_name !== 'help.page.factory_lollipop') {
      return NULL;
    }

    $text = (string) file_get_contents($this->moduleExtensionList->getPath('factory_lollipop') . '/README.md');
    if ($this->filterManager === NULL || !$this->moduleHandler->moduleExists('markdown')) {
      return '<pre>' . $text . '</pre>';
    }

    // Use the Markdown filter to render the README.
    $settings = $this->configFactory->get('markdown.settings')->getRawData();
    $filter = $this->filterManager->createInstance('markdown', ['settings' => $settings]);

    return $filter->process($text, 'en')->getProcessedText();
  }

}
