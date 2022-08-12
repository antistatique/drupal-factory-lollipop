<?php

namespace Drupal\Tests\factory_lollipop\Kernel;

use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;

/**
 * Provides a base class for Factory Lollipop kernel tests.
 */
abstract class LollipopKernelTestBase extends EntityKernelTestBase {

  /**
   * The Factory Lollipop fixture factory.
   *
   * @var \Drupal\factory_lollipop\FixtureFactory
   */
  protected $factoryLollipop;

  /**
   * {@inheritdoc}
   *
   * Note that when a child class declares its own $modules list, that list
   * doesn't override this one, it just extends it.
   *
   * @var array
   */
  protected static $modules = [
    'factory_lollipop',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->factoryLollipop = $this->container->get('factory_lollipop.fixture_factory');
  }

}
