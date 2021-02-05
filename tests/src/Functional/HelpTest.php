<?php

namespace Drupal\Tests\factory_lollipop\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Verify help display and user access to help based on permissions.
 *
 * @group factory_lollipop
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
  public static $modules = ['help_test', 'factory_lollipop'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * The admin user that will be created.
   */
  protected $adminUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Create users.
    $this->adminUser = $this->drupalCreateUser([
      'access administration pages',
      'view the administration theme',
      'administer permissions',
    ]);
  }

  /**
   * Logs in users, tests help page.
   */
  public function testHelp() {
    // Log in the admin user to ensure as many admin links appear as possible on
    // the module overview pages.
    $this->drupalLogin($this->adminUser);

    $this->drupalGet('admin/help/factory_lollipop');
    $this->assertSession()->statusCodeEquals(200);
  }

}
