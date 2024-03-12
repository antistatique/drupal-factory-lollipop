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
  protected static $modules = ['help', 'help_test', 'factory_lollipop'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

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

    $permissions = [
      'access administration pages',
    ];

    // Since Drupal 10.2 accessing help page require a new permission.
    if (version_compare(\Drupal::VERSION, '10.2', '>=')) {
      $permissions = [
        'access administration pages',
        'access help pages',
      ];
    }

    // Create users.
    $this->adminUser = $this->drupalCreateUser($permissions);
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
