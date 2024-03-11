<?php

namespace Drupal\Tests\factory_lollipop\Unit\Resolver\FactoryType;

use Prophecy\PhpUnit\ProphecyTrait;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\File\FileSystemInterface;
use Drupal\factory_lollipop\FactoryType\FileFactoryType;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\FileFactoryType
 *
 * @group factory_lollipop
 */
class FileFactoryTypeTest extends UnitTestCase {

  use ProphecyTrait;
  /**
   * The File Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\FileFactoryType
   */
  protected $fileFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $file_system = $this->prophesize(FileSystemInterface::class);

    $system_file_config = $this->prophesize(ImmutableConfig::class);
    $system_file_config->get('default_scheme')->willReturn('public');
    $config_factory = $this->prophesize(ConfigFactoryInterface::class);
    $config_factory->get('system.file')->willReturn($system_file_config->reveal());

    $this->fileFactoryTypeResolver = new FileFactoryType($config_factory->reveal(), $file_system->reveal());
  }

  /**
   * @covers ::shouldApply
   */
  public function testShouldApply(): void {
    self::assertTrue($this->fileFactoryTypeResolver->shouldApply('file'));
    self::assertFalse($this->fileFactoryTypeResolver->shouldApply('File'));
    self::assertFalse($this->fileFactoryTypeResolver->shouldApply('phasellus'));
  }

}
