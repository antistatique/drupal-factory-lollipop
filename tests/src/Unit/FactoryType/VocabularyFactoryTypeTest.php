<?php

namespace Drupal\Tests\factory_lollipop\Unit\Resolver\FactoryType;

use Drupal\factory_lollipop\FactoryType\VocabularyFactoryType;
use Drupal\Tests\UnitTestCase;

/**
 * @coversDefaultClass \Drupal\factory_lollipop\FactoryType\VocabularyFactoryType
 *
 * @group factory_lollipop
 */
class VocabularyFactoryTypeTest extends UnitTestCase {

  /**
   * The Vocabulary Factory resolver.
   *
   * @var \Drupal\factory_lollipop\FactoryType\VocabularyFactoryType
   */
  protected $vocabularyFactoryTypeResolver;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->vocabularyFactoryTypeResolver = new VocabularyFactoryType();
  }

  /**
   * @covers ::shouldApply
   */
  public function testShouldApply(): void {
    self::assertTrue($this->vocabularyFactoryTypeResolver->shouldApply('vocabulary'));
    self::assertFalse($this->vocabularyFactoryTypeResolver->shouldApply('Vocabulary'));
    self::assertFalse($this->vocabularyFactoryTypeResolver->shouldApply('taxonomy'));
    self::assertFalse($this->vocabularyFactoryTypeResolver->shouldApply('Taxonomy Vocabulary'));
    self::assertFalse($this->vocabularyFactoryTypeResolver->shouldApply('phasellus'));
  }

}
