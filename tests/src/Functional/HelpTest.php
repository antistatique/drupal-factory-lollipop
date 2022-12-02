<?php

namespace Drupal\Tests\factory_lollipop\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Verify help display and user access to help based on permissions.
 *
 * @group factory_lollipop
 * @group factory_lollipop_functional
 */
class HelpTest extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * The help_test module implements hook_help() but does not provide a module
   * overview page. The factory_lollipop module has a page section plugin that
   * returns no links.
   *
   * @var array
   */
  protected static $modules = ['help_test', 'factory_lollipop'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'starterkit_theme';

  /**
   * The admin user.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create users.
    $this->adminUser = $this->drupalCreateUser([
      'access administration pages',
      'view the administration theme',
      'administer permissions',
    ]);
    $this->drupalLogin($this->adminUser);
  }

  /**
   * Logs in users, tests help page.
   */
  public function testHelp() {
    $this->drupalGet('admin/help/factory_lollipop');
    $this->assertSession()->statusCodeEquals(200);
  }

}
