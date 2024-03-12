<?php

namespace Drupal\Tests\factory_lollipop\Functional;

use Drupal\Tests\system\Functional\Module\ModuleTestBase;

/**
 * Tests install / uninstall of Factory Lollipop.
 *
 * @group factory_lollipop
 * @group factory_lollipop_functional
 */
class InstallUninstallTest extends ModuleTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'starterkit_theme';

  /**
   * Ensure Factory Lollipop can be installed.
   */
  public function testInstall(): void {
    // Makes sure the module is not already installed.
    $this->assertModules(['factory_lollipop'], FALSE);

    // Attempt to install the factory_lollipop module.
    $edit = [];
    $edit['modules[factory_lollipop][enable]'] = 'factory_lollipop';
    $this->drupalGet('admin/modules');
    $this->submitForm($edit, 'Install');

    // Since Drupal 10.3 the installation message has been changed.
    if (version_compare(\Drupal::VERSION, '10.3', '>=')) {
      $this->assertSession()->pageTextContains('Module Factory Lollipop has been installed.');
    }
    else {
      $this->assertSession()->pageTextContains('Module Factory Lollipop has been enabled');
    }

    // Makes sure the module has been installed.
    $this->assertModules(['factory_lollipop'], TRUE);
  }

  /**
   * Ensure Factory Lollipop can be uninstalled.
   */
  public function testUninstall(): void {
    // Makes sure the module is installed.
    $this->container->get('module_installer')->install(['factory_lollipop']);

    // Attempt to uninstall the factory_lollipop module.
    $edit['uninstall[factory_lollipop]'] = TRUE;
    $this->drupalGet('admin/modules/uninstall');
    $this->submitForm($edit, 'Uninstall');
    // Confirm uninstall.
    $this->submitForm([], 'Uninstall');
    $this->assertSession()->responseContains('The selected modules have been uninstalled.');

    // Makes sure the module has been uninstalled.
    $this->assertModules(['factory_lollipop'], FALSE);
  }

}
