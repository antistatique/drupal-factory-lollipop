<?php

namespace Drupal\Tests\factory_lollipop_paragraphs\Unit\FactoryType;

use Drupal\factory_lollipop_paragraphs\FactoryType\ParagraphFactoryType;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop_paragraphs\FactoryType\ParagraphFactoryType
 *
 * @group factory_lollipop
 * @group factory_lollipop_paragraphs
 */
class ParagraphFactoryTypeTest extends UnitTestCase {

  /**
   * The Paragraph Type Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\ParagraphFactoryType
   */
  protected $paragraphFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->paragraphFactoryTypeResolver = new ParagraphFactoryType();
  }

  /**
   * @covers ::shouldApply
   */
  public function testShouldApply(): void {
    self::assertTrue($this->paragraphFactoryTypeResolver->shouldApply('paragraph'));
    self::assertFalse($this->paragraphFactoryTypeResolver->shouldApply('paragraphs'));
    self::assertFalse($this->paragraphFactoryTypeResolver->shouldApply('Paragraph'));
    self::assertFalse($this->paragraphFactoryTypeResolver->shouldApply('phasellus'));
  }

}
