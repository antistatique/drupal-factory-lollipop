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
  protected $defaultTheme = 'stark';

  /**
   * Ensure Factory Lollipop can be installed.
   */
  public function testInstall(): void {
    // Makes sure the module is not already installed.
    $this->assertModules(['factory_lollipop'], FALSE);

    // Attempt to install the factory_lollipop module.
    $edit = [];
    $edit['modules[factory_lollipop][enable]'] = 'factory_lollipop';
    $this->drupalPostForm('admin/modules', $edit, 'Install');
    $this->assertSession()->responseContains('Module Factory Lollipop has been enabled.', 'Modules status has been updated.');

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
    $this->drupalPostForm('admin/modules/uninstall', $edit, 'Uninstall');
    $this->drupalPostForm(NULL, NULL, 'Uninstall');
    $this->assertSession()->responseContains('The selected modules have been uninstalled.', 'Modules status has been updated.');

    // Makes sure the module has been uninstalled.
    $this->assertModules(['factory_lollipop'], FALSE);
  }

}
