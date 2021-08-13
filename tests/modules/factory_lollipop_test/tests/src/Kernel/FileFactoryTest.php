<?php

namespace Drupal\Tests\factory_lollipop_test\Kernel;

use Drupal\file\FileInterface;
use Drupal\Tests\factory_lollipop\Kernel\LollipopKernelTestBase;

/**
 * Example of Factory Lollipop usage for File.
 *
 * @group factory_lollipop
 * @group factory_lollipop_example
 */
class FileFactoryTest extends LollipopKernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'file',
    'factory_lollipop_test',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('file');
  }

  /**
   * Ensure defined File of any kind can be created.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\FileFactoryType::create
   */
  public function testCreate(): void {
    $this->factoryLollipop->loadDefinitions(['file']);

    $file = $this->factoryLollipop->create('file_tmp');
    self::assertInstanceOf(FileInterface::class, $file);
    self::assertStringStartsWith('temporary://', $file->getFileUri());

    $file = $this->factoryLollipop->create('file_public');
    self::assertInstanceOf(FileInterface::class, $file);
    self::assertStringStartsWith('public://', $file->getFileUri());
  }

  /**
   * Ensure the FileFactory is overridable.
   *
   * @covers \Drupal\factory_lollipop\FixtureFactory::loadDefinitions
   * @covers \Drupal\factory_lollipop\FixtureFactory::define
   * @covers \Drupal\factory_lollipop\FixtureFactory::association
   * @covers \Drupal\factory_lollipop\FixtureFactory::create
   * @covers \Drupal\factory_lollipop\FactoryType\FileFactoryType::create
   */
  public function testDefineOverride() {
    $path = $this->root . '/core/tests/fixtures/files/image-1.png';

    $this->factoryLollipop->loadDefinitions(['file']);

    $file = $this->factoryLollipop->create('file_tmp', ['path' => $path]);
    self::assertInstanceOf(FileInterface::class, $file);
    self::assertSame('temporary://image-1.png', $file->getFileUri());

    $file = $this->factoryLollipop->create('file_public', ['path' => $path]);
    self::assertInstanceOf(FileInterface::class, $file);
    self::assertStringStartsWith('public://image-1.png', $file->getFileUri());
  }

}
