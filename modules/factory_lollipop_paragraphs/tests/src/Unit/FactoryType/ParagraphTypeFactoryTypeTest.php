<?php

namespace Drupal\Tests\factory_lollipop_paragraphs\Unit\FactoryType;

use Drupal\factory_lollipop_paragraphs\FactoryType\ParagraphTypeFactoryType;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop_paragraphs\FactoryType\ParagraphTypeFactoryType
 *
 * @group factory_lollipop
 * @group factory_lollipop_paragraphs
 */
class ParagraphTypeFactoryTypeTest extends UnitTestCase {

  /**
   * The Paragraph Type Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\ParagraphTypeFactoryType
   */
  protected $paragraphTypeFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->paragraphTypeFactoryTypeResolver = new ParagraphTypeFactoryType();
  }

  /**
   * @covers ::shouldApply
   */
  public function testShouldApply(): void {
    self::assertTrue($this->paragraphTypeFactoryTypeResolver->shouldApply('paragraph type'));
    self::assertFalse($this->paragraphTypeFactoryTypeResolver->shouldApply('paragraphs type'));
    self::assertFalse($this->paragraphTypeFactoryTypeResolver->shouldApply('paragraph_type'));
    self::assertFalse($this->paragraphTypeFactoryTypeResolver->shouldApply('Paragraph type'));
    self::assertFalse($this->paragraphTypeFactoryTypeResolver->shouldApply('phasellus'));
  }

}
